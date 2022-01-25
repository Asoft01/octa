<?php

namespace App\Http\Composers\Frontend;

use App\Models\AcCategory;
use App\Models\AcTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\AcLive;

/**
 * Class GlobalComposer.
 */
class NavComposer {

	/**
	 * Bind data to the view.
	 *
	 * @param View $view
	 */
	public function compose(View $view) {
		$domain = request()->domain;

		if (is_model($domain)) {
			$categories = Cache::remember("navSearchCategories_for_{$domain->slug}", 21600, function() use ($domain) {
				// required to avoid "Column 'id' in where clause is ambiguous" error
				$categories_table_id = app(AcCategory::class)->getTable() . '.id';
				return $domain->categories()
						->with(['contents', 'contents.categories', 'contents.contentable'])
						->where($categories_table_id, '!=', 1)
						->orderBy('title')
						->get();
			});

			$alltags = Cache::remember("navSearchAllTags_for_{$domain->slug}", 21600, function() use ($domain) {
				return $domain->tags()
					->orderBy('title')
					->get(['title', 'id'])
					->pluck('title', 'id')
					->toArray();
			});

			$tags = Cache::remember("navSearchTags_for_{$domain->slug}", 21600, function() use ($categories, $domain) {
				return $domain->tags()
					->withCount('contents')
					->whereNotIn('title', $categories->pluck('title')->all())
					->orderBy('contents_count', 'desc')
					->limit(12)
					->get(['title', 'contents_count'])
					->transform(function($item) {
						return (object) [
							'title' => $item->title,
							'nbvid' => $item->contents_count
						];
					})
					->all();
			});
		} else {
			$categories = collect();
			$alltags = collect();
			$tags = collect();
		}

		// if any streams is up and running send that info to nav blade (turn font-awesome red)
		$isStreaming = AcLive::where('isStreaming', 1)->exists();

//		$categories = Cache::remember('navSearchCategories', 21600, function () {
//			return AcCategory::with('contents.contentable')->where('id', '!=', 1)->orderBy('title')->get();
//		});

//		$alltags = Cache::remember('navSearchAllTags', 21600, function () {
//			return AcTag::OrderBy('title')->get()->pluck('title','id')->toArray();
//		});

		// the idea is to not select the tags that are categories - order by number of videos limit to 20
//		$tags = Cache::remember('navSearchTags', 21600, function() use ($categories) {
//			return DB::select("select ac_tags.title , count(ac_tag_id) as nbvid from ac_content_tags
//				left join ac_tags on ac_tags.id = ac_content_tags.ac_tag_id
//				where upper(ac_tags.title) not in('" . strtoupper(implode('\', \'', $categories->pluck('title')->toArray())) . "')
//				group by ac_content_tags.ac_tag_id, ac_tags.title
//				ORDER BY `nbvid` DESC LIMIT 12");
//		});

		$view->with(compact('categories', 'tags', 'alltags', 'isStreaming'));
	}

}
