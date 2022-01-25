<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\AC\ContentFilterController;
use App\Models\AcDomain;
use App\Models\AcContent;
use Illuminate\Http\Request;

/**
 * Class DashboardController.
 */
class TagController extends Controller {

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(AcDomain $domain, $slug) {
		$key = urlencode(mb_strtolower(urldecode($slug)));
		$tag = $domain->tags->filter(function($tag) use ($key) { return $tag->slug === $key; })->first();
		$model = $domain->tags->filter(function($tag) use ($key) { return $tag->slug === $key; })->first();
		abort_unless($model, 404);

		$contents = $model->contents()
			->with([
				'contentable.mentor.user',
				'categories' => function($query) use ($domain) {
					$query->whereDomain($domain);
				},
				//'tags' => function($query) use ($domain, $model) {
				//	$query->where('id', '!=', $model->id)->where('domain_id', $domain->id);
				//}
			])
			->where(function($query) use ($domain) {
				$column = $query->qualifyColumn('contentable_type');
				$query->whereDomain($domain)->orWhere($column, 'MorphAsset');
			})
			->get(['id', 'contentable_type', 'contentable_id']);

		$accounts = $contents->where('contentable_type', '!=', 'MorphAsset')->pluck('contentable.mentor');
		$categories = $contents->pluck('categories')->flatten();
		$types = $contents->unique('contentable_type')->pluck('content_type', 'contentable_type')->sort()->all();

		// related tags
		$related = $contents->pluck('tags')
			->flatten(1)
			->unique('id')
			->where('id', '!=', $tag->id)
			->whereIn('id', $domain->tags->pluck('id'))
			->pluck('title', 'slug')
			->sort();

		return view('frontend.ac.contents', [
			'title' => $model->title,
			'heading' => "Tag: {$model->title}",
			'filters' => [
				'categories' => $categories->pluck('title', 'id')->normalSort()->all(),
				'contributors' => $accounts->pluck('full_name', 'id')->normalSort()->all(),
				'types' => $types
			],
			'recommend' => [
				'categories' => $categories->pluck('id')->countBy()->sortDesc()->keys(),
				'contributors' => $accounts->pluck('id')->countBy()->sortDesc()->keys()
			],
			'relatedtags' => $related,
			'requires' => [
				'tags' => [$model->id]
			],
			'sorting' => [
				'Contributor' => 'sort_contributor',
				'Date' => 'ac_contents.display_start',
				'Duration' => 'sort_duration',
				'Likes' => 'sort_thumbs',
				'Title' => 'ac_contents.title'
			]
		]);
	}

	public function infiniteScrolling(Request $request, AcDomain $domain, $slug) {
		$after = (string) $request->query('after', '');
		$before = (string) $request->query('before', '');
		$page = intval($request->query('page'), 10) ?: 1;
		$show = intval($request->query('show'), 10) ?: 10;
		$html = '';

		$key = urlencode(mb_strtolower(urldecode($slug)));
		$tag = $domain->tags->filter(function($tag) use ($key) { return $tag->slug === $key; })->first();

		abort_unless($tag, 404);
		
		$query = AcContent::isAvailable()->whereTag($tag)->where(function ($query) use ($domain) {
			$query->whereDomain($domain)->orWhere('contentable_type', 'MorphAsset');
		});

		$total = $query->count();
		$pages = ceil($total / $show);
		$offset = max(0, $page - 1) * $show;
		
		$contents = $query
			->with(['contentable', 'contentable.mentor'])
			->displayOrder()
			->offset($offset)
			->limit($show)
			->get();

		foreach($contents as $content) {
			$view = view('frontend.includes.videoitem', [
				'content' => $content,
				'show_category' => true,
				'lazyload' => false
			]);
			$html .= $before . $view->render() . $after;
		}

		return response()->json(compact('html', 'page', 'pages', 'show', 'total'));
	}

}
