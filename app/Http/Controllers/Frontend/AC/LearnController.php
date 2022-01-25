<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\AC\ContentFilterController;
use App\Models\AcCategory;
use App\Models\AcContent;
use App\Models\AcDomain;
use App\Models\AcLive;
use App\Models\AcTag;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Class DashboardController.
 */
class LearnController extends Controller {

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function home(AcDomain $domain) {
		$content_ids = $domain->getContentIds();

		// content of the day based on display date and(need to manage timezone and between...)
		$cotd = AcContent::with([
				'categories',
				'contentable',
				'tags' => function($query) use ($domain) { $query->where('domain_id', $domain->id); }
			])
			->whereIn('id', $content_ids)
			->where('cotd_start', '<=', now())
			->orderBy('cotd_start', 'desc')
			->first();
		
		// mentors
		$mentors = Role::with('users.account')->where('name', 'mentor')->first();
		$mentors->users = $mentors->users->filter(function($user) use ($domain) {
			return $user->account->ac_domain_id == $domain->id;
		});

		// last reviews
		$lastreviews = AcContent::with(['contentable', 'categories'])
			->whereIn('id', $content_ids)
			->where('contentable_type', 'MorphReview')
			->isAvailable()
			->displayOrder()
			->take(8)
			->get();

		// last announcement
		$lastannouncements = $domain->announcements()
			->with(['contents', 'contents.contentable', 'contents.categories'])
			->first();

		// last assets
		$assets = AcContent::with(['contentable', 'categories'])
			//->whereIn('id', $content_ids) // assets do not have categories yet
			->where('contentable_type', 'MorphAsset')
			->isAvailable()
			->displayOrder()
			->take(8)
			->get();

		$lastcontents = AcContent::with(['contentable', 'categories'])
			->whereIn('id', $content_ids)
			->whereIn('contentable_type', ['MorphReview', 'MorphVideo'])
			->when($cotd, function($query, $cotd) {
				return $query->where('id', '!=', $cotd->id);
			})
			->displayOrder()
			->take(20)
			->get();
		
		$twitch = AcLive::where('user_login', config('services.twitch.user_login'))->where('isStreaming', 1)->first();

		return view('frontend.ac.home', compact('twitch', 'cotd', 'mentors', 'lastreviews', 'lastannouncements', 'assets', 'lastcontents'));
	}

	public function library(AcDomain $domain) {
		$content_ids = $domain->getContentIds();
		$categories_table_id = app(AcCategory::class)->getTable() . '.id'; // Avoids "Column 'id' in where clause is ambiguous" error.

		// content of the day based on display date and (need to manage timezone and between...)
		$cotd = AcContent::with([
				'categories',
				'contentable',
				'tags' => function($query) use ($domain) { $query->where('domain_id', $domain->id); }
			])
			->whereIn('id', $content_ids)
			->where('cotd_start', '<=', now())
			->orderBy('cotd_start', 'desc')
			->first();

		$lastcontents = AcContent::with(['contentable', 'categories'])
			->whereIn('id', $content_ids)
			->whereIn('contentable_type', ['MorphReview', 'MorphVideo'])
			->where('id', '!=', $cotd->id)
			->isAvailable()
			->displayOrder()
			->take(20)
			->get();

		$categories = $domain->categories()
			->with(['contents', 'contents.contentable', 'contents.categories'])
			->where($categories_table_id, '!=', 1)
			->orderBy('seq')
			->get()
			->keyBy('id');

		// Remove contents that are not playlists from Learning Paths category.
		if ($categories->has(23)) {
			$categories[23]->setRelation('contents', $categories[23]->contents->filter->isPlaylist());
		}

		return view('frontend.ac.learn', compact('cotd', 'lastcontents', 'categories'));
	}

	public function preview(AcDomain $domain, $slug) {
		$content_ids = $domain->getContentIds();

		// content of the day based on display date and(need to manage timezone and between...)
		$cotd = AcContent::with([
				'categories',
				'contentable',
				'tags' => function($query) use ($domain) { $query->where('domain_id', $domain->id); }
			])
			->whereIn('id', $content_ids)
			->where('slug', $slug)
			->first();

		$lastcontents = AcContent::with(['contentable', 'categories'])
			->whereIn('id', $content_ids)
			->where('slug', $slug)
			->take(10)
			->get();

		// required to avoid "Column 'id' in where clause is ambiguous" error
		$categories_table_id = app(AcCategory::class)->getTable() . '.id';
		$categories = $domain->categories()
			->with(['contents' => function($query) use ($content_ids) {
				$query->with(['contentable', 'categories'])->where('id', $content_ids);
			}])
			->where($categories_table_id, '!=', 1)
			->orderBy('seq')
			->get();

		return view('frontend.ac.learn', compact('cotd', 'lastcontents', 'categories'));
	}

	public function all(AcDomain $domain) {
		$title = 'All Contents';
		$roles = ['mentor', 'contributor'];

		$accounts = $domain->accounts()->whereRoles($roles)->withCount('reviews')->withCount('videos')->get(['id', 'user_id']);
		$categories = $domain->categories()->withCount('contents')->get(['id', 'title']);
		$tags = $domain->tags()->withCount('contents')->get(['id', 'title']);
		$types = AcContent::select('contentable_type')->setEagerLoads([])->distinct()->get()->pluck('content_type', 'contentable_type')->sort()->all();

		$accounts_contents_count = $accounts->keyBy('id')->map(function($account) {
			return $account->reviews_count + $account->videos_count;
		});

		return view('frontend.ac.contents', [
			'title' => $title,
			'heading' => $title,
			'filters' => [
				'categories' => $categories->pluck('title', 'id')->normalSort()->all(),
				'contributors' => $accounts->pluck('full_name', 'id')->normalSort()->all(),
				'tags' => $tags->pluck('title', 'id')->normalSort()->all(),
				'types' => $types
			],
			'recommend' => [
				'categories' => $categories->pluck('contents_count', 'id')->sortDesc()->keys(),
				'contributors' => $accounts_contents_count->sortDesc()->keys(),
				'tags' => $tags->pluck('contents_count', 'id')->sortDesc()->keys()
			],
			'requires' => [],
			'sorting' => [
				'Contributor' => 'sort_contributor',
				'Date' => 'ac_contents.display_start',
				'Duration' => 'sort_duration',
				'Likes' => 'sort_thumbs',
				'Title' => 'ac_contents.title'
			],
			'search' => true
		]);
	}

	public function category(AcDomain $domain, $category) {
		$model = $domain->categories()->where('title', urldecode($category))->firstOrFail();

		$requires = ['categories' => array($model->id)];

		if ($model->id == 23) {
			// Category "Learning Paths" should only show playlists.
			$requires['types'] = ["Playlist" => "MorphPlaylist"];
			$contents = $model->contents()
				->with(['contentable.mentor.user', 'tags'])->without('categories')
				->where('contentable_type', 'MorphPlaylist')->isAvailable()
				->get(['id', 'contentable_type', 'contentable_id']);
		} else {
			$contents = $model->contents()
				->with(['contentable.mentor.user', 'tags'])->without('categories')
				->where('contentable_type', '!=', 'MorphAsset')->isAvailable()
				->get(['id', 'contentable_type', 'contentable_id']);
		}

		$accounts = $contents->pluck('contentable.mentor'); //->pluck('full_name', 'contentable.mentor.id')->normalSort();
		//$tags = $contents->pluck('tags')->where('domain_id', $domain->id)->flatten()->pluck('title', 'id')->normalSort()->all();
		$tags = $contents->pluck('tags')->flatten();
		//$types = $contents->unique('contentable_type')->pluck('content_type', 'contentable_type')->sort()->all();

		return view('frontend.ac.contents', [
			'title' => $model->title,
			'heading' => "Category: {$model->title}",
			'filters' => [
				'contributors' => $accounts->pluck('full_name', 'id')->normalSort()->all(),
				'tags' => $tags->pluck('title', 'id')->normalSort()->all()
			],
			'recommend' => [
				'contributors' => $accounts->pluck('id')->countBy()->sortDesc()->keys(),
				'tags' => $tags->pluck('id')->countBy()->sortDesc()->keys()
			],
			'requires' => $requires,
			'sorting' => [
				'Contributor' => 'sort_contributor',
				'Date' => 'ac_contents.display_start',
				'Duration' => 'sort_duration',
				'Likes' => 'sort_thumbs',
				'Title' => 'ac_contents.title'
			]
		]);
	}

	public function categoryInfiniteScrolling(Request $request, AcDomain $domain, $category) {
		$after = (string) $request->query('after', '');
		$before = (string) $request->query('before', '');
		$page = intval($request->query('page'), 10) ?: 1;
		$show = intval($request->query('show'), 10) ?: 10;
		$html = '';

		$query = $domain->categories()->where('title', urldecode($category))->firstOrFail()->contents()->isAvailable();

		$total = $query->count('id');
		$pages = ceil($total / $show);
		$offset = max(0, $page - 1) * $show;
		
		$contents = $query->with('contentable')->displayOrder()->offset($offset)->limit($show)->get();

		foreach($contents as $content) {
			$view = view('frontend.includes.videoitem', [
				'content' => $content,
				'lazyload' => false
			]);
			$html .= $before . $view->render() . $after;
		}

		return response()->json(compact('html', 'page', 'pages', 'show', 'total'));
	}

	public function search() {
		$categories = Cache::remember('navSearchCategories', 21600, function () {
				return AcCategory::with('contents.contentable')->where('id', '!=', 1)->orderBy('title')->get();
			});

		$alltags = Cache::remember('navSearchAllTags', 21600, function () {
				return AcTag::OrderBy('title')->get()->pluck('title', 'id')->toArray();
			});

		// the idea is to not select the tags that are categories - order by number of videos limit to 20
		$tags = Cache::remember('navSearchTags', 21600, function () use ($categories) {
				return DB::select("select ac_tags.title , count(ac_tag_id) as nbvid from ac_content_tags
            left join ac_tags on ac_tags.id = ac_content_tags.ac_tag_id
            where upper(ac_tags.title) not in('" . strtoupper(implode('\', \'', $categories->pluck('title')->toArray())) . "')
            group by ac_content_tags.ac_tag_id, ac_tags.title
            ORDER BY `nbvid` DESC LIMIT 12");
			});

		return view('frontend.ac.search', compact('categories', 'alltags', 'tags'));
	}
}
