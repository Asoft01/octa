<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Models\AcContent;
use App\Models\AcDomain;
use Illuminate\Http\Request;

class AssetController extends Controller {

	public function index(AcDomain $domain) {

		// if ever assets have categories
		// $contents = AcContent::with('tags')->where('contentable_type', 'MorphAsset')->whereDomain($domain)->isAvailable()->get();

		$contents = AcContent::with('tags')->where('contentable_type', 'MorphAsset')->isAvailable()->get();
		$tags = $contents->pluck('tags')->flatten()->where('domain_id', $domain->id);

		return view('frontend.ac.contents', [
			'title' => 'Assets',
			'heading' => 'Assets',
			'filters' => [
				'tags' => $tags->pluck('title', 'id')->normalSort()->all()
			],
			'recommend' => [
				'tags' => $tags->pluck('id')->countBy()->sortDesc()->keys()
			],
			'requires' => [
				'types' => ['MorphAsset']
			],
			'sorting' => [
				// 'Contributor' => 'sort_contributor',
				'Date' => 'ac_contents.display_start',
				// 'Duration' => 'sort_duration',
				'Likes' => 'sort_thumbs',
				'Title' => 'ac_contents.title'
			]
		]);
	}

	public function infiniteScrolling(Request $request) {
		$after = (string) $request->query('after', '');
		$before = (string) $request->query('before', '');
		$page = intval($request->query('page'), 10) ?: 1;
		$show = intval($request->query('show'), 10) ?: 10;
		$html = '';

		$query = AcContent::with('contentable')->where('contentable_type', 'MorphAsset')->isAvailable();

		$total = $query->count('id');
		$pages = ceil($total / $show);
		$offset = max(0, $page - 1) * $show;

		$contents = $query->displayOrder()->offset($offset)->limit($show)->get();
		
		foreach($contents as $content) {
			$view = view('frontend.includes.videoitem', [
				'content' => $content,
				'lazyload' => false
			]);
			$html .= $before . $view->render() . $after;
		}

		return response()->json(compact('html', 'page', 'pages', 'show', 'total'));
	}
}
