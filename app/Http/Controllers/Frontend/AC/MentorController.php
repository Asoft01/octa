<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\HomeController;
use App\Models\AcAccount;
use App\Models\AcCategory;
use App\Models\AcContent;
use App\Models\AcDomain;
use App\Models\AcPlaylist;
use App\Models\Auth\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\DB;

class MentorController extends Controller {

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(AcDomain $domain) {
		$mentors = Role::with('users.account')->where('name', 'mentor')->first();
		$mentors->users = $mentors->users->filter(function($user) use ($domain) {
			return $user->account->ac_domain_id == $domain->id;
		});
		return view('frontend.ac.mentors', compact('mentors'));
	}

	public function account($domain, $account) {
		$tabs = [];

		$has_playlists = AcContent::query()
			->whereAccount($account, 'App\Models\AcPlaylist')
			->whereDomain($domain)
			->isAvailable()
			->exists();

		$has_reviews = AcContent::query()
			->whereAccount($account, 'App\Models\AcReview')
			->whereDomain($domain)
			->isAvailable()
			->exists();
		
		$has_videos = AcContent::query()
			->whereAccount($account, 'App\Models\AcVideo')
			->whereDomain($domain)
			->isAvailable()
			->exists();
		
		if ($has_reviews) {
			$tabs[] = [
				'id' => 'reviews',
				'title' => 'Reviews',
				'url' => route('frontend.reviewer.infinite', $account->slug)
			];
		}

		if ($has_videos) {
			$tabs[] = [
				'id' => 'videos',
				'title' => 'Videos',
				'url' => route('frontend.contributor.infinite', $account->slug)
			];
		}

		if ($has_playlists) {
			$tabs[] = [
				'id' => 'playlists',
				'title' => 'Playlists',
				'url' => route('frontend.account.playlists.infinite', $account->slug)
			];
		}

		return view('frontend.ac.reviewer', compact('account', 'tabs'));
	}

	public function reviewer(AcDomain $domain, $slug) {
		$account = $domain->accounts()->where('slug', $slug)->firstOrFail();

		if (!$account->user->hasRole('mentor')) {
			if ($account->user->hasRole('contributor')) {
				return redirect()->route('frontend.contributor', $account->slug);
			}
			return abort(404);
		}

		return $this->account($domain, $account);
	}

	public function contributor(AcDomain $domain, $slug) {
		$account = $domain->accounts()->where('slug', $slug)->firstOrFail();
		if ($account->user->hasRole('mentor')) {
			return redirect()->route('frontend.reviewer', $slug);
		}
		if ($account->user->hasRole('contributor')) {
			return $this->account($domain, $account);
		}
		abort(404);
	}

	public function reviewerInfiniteScrolling(Request $request, AcDomain $domain, $slug) {
		$after = (string) $request->query('after', '');
		$before = (string) $request->query('before', '');
		$page = intval($request->query('page'), 10) ?: 1;
		$show = intval($request->query('show'), 10) ?: 10;

		$account = $domain->accounts()->where('slug', $slug)->firstOrFail();
		abort_unless($account->user->hasRole('mentor'), 404);

		$domain_category_ids = $domain->categories->pluck('id')->all();
		$reviews = $account->reviews()
			->with(['content' => function($query) {
				$query->with('categories')->without('contentable')->where('contentable_type', 'MorphReview')->isAvailable();
			}])
			->get()
			->filter(function($review) use ($domain_category_ids) {
				$categories = optional($review->content)->categories;
				return $categories ? $categories->whereIn('id', $domain_category_ids)->isNotEmpty() : false;
			})
			->sortByDesc('content.display_start');

		$html = '';

		foreach($reviews->forPage($page, $show) as $review) {
			$review->content->setRelation('contentable', $review);
			$with = [
				'content' => $review->content,
				'login' => false,
				'show_details' => false,
				'lazyload' => false
			];
			$html .= $before . view('frontend.includes.videoitem')->with($with)->render() . $after;
		}
		
		$total = $reviews->count();
		$pages = ceil($total / $show);

		return response()->json(compact('html', 'page', 'pages', 'show', 'total'));
	}

	public function contributorInfiniteScrolling(Request $request, $slug) {
		$after = (string) $request->query('after', '');
		$before = (string) $request->query('before', '');
		$page = intval($request->query('page'), 10) ?: 1;
		$show = intval($request->query('show'), 10) ?: 10;

		$account = AcAccount::where('slug', $slug)->with('user')->firstOrFail();
		abort_unless($account->user->hasRole('contributor'), 404);

		$videos = $account->videos()
			->without('mentor')
			->with(['content.categories' => function($q) { $q->without('contents'); }])
			->whereHas('content', function($q) { $q->isAvailable(); })
			->get()
			->sortByDesc('content.display_start');

		$html = '';

		foreach($videos->forPage($page, $show) as $video) {
			$video->content->setRelation('contentable', $video);
			$with = [
				'content' => $video->content,
				'login' => false,
				'show_details' => false,
				'lazyload' => false
			];
			$html .= $before . view('frontend.includes.videoitem')->with($with)->render() . $after;
		}
		
		$total = $videos->count();
		$pages = ceil($total / $show);

		return response()->json(compact('html', 'page', 'pages', 'show', 'total'));
	}

	public function playlistsInfiniteScrolling(Request $request, $slug) {
		$after = (string) $request->query('after', '');
		$before = (string) $request->query('before', '');
		$page = intval($request->query('page'), 10) ?: 1;
		$show = intval($request->query('show'), 10) ?: 10;

		$account = AcAccount::where('slug', $slug)->with('user')->firstOrFail();

		$playlists = AcPlaylist::where('user_id', $account->user->id)
			->without('mentor')
			->with(['content.categories' => function($q) { $q->without('contents'); }])
			// ->whereHas('content', function($q) { $q->isAvailable(); })
			->get()
			->sortByDesc('content.display_start');

		$html = '';

		foreach($playlists->forPage($page, $show) as $playlist) {
			$playlist->content->setRelation('contentable', $playlist);
			$with = [
				'content' => $playlist->content,
				'login' => false,
				'show_details' => false,
				'lazyload' => false
			];
			$html .= $before . view('frontend.includes.videoitem')->with($with)->render() . $after;
		}
		
		$total = $playlist->count();
		$pages = ceil($total / $show);

		return response()->json(compact('html', 'page', 'pages', 'show', 'total'));
	}

}
