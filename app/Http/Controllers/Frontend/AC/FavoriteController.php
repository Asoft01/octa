<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Models\AcCategory;
use App\Models\AcContent;
use App\Models\AcDomain;
use App\Models\AcFavorite;
use Illuminate\Http\Request;
use stdClass;

class FavoriteController extends Controller {

	public function index(Request $request, AcDomain $domain) {
		$favorite_ids = $request->user()->favorites()->latest()->get(['content_id'])->pluck('content_id')->all();
		
		$categories = AcCategory::with(['contents' => function($query) use ($favorite_ids) {
			$query->with(['contentable', 'categories'])->whereIn('id', $favorite_ids)->isAvailable();
		}])->get()->filter(function($category) {
			return $category->contents->isNotEmpty();
		})->sortBy('seq');

		// Some contents, like assets, do not always have categories.
		// So we're going to grab all the contents that didn't eager load with the categories.
		$categorized_ids = $categories->pluck('contents')->flatten(1)->pluck('id')->all();
		$uncategorized_ids = array_diff($favorite_ids, $categorized_ids);
		$uncategorized_contents = AcContent::with(['contentable', 'categories'])->whereIn('id', $uncategorized_ids)->isAvailable()->get();

		// Create temporary categories for uncategorized contents, so the view can use them.
		if ($uncategorized_contents) {

			// Temporary category for assets.
			$assets = $uncategorized_contents->where('contentable_type', 'MorphAsset');
			if ($assets) {
				$object = new stdClass(); // new AcCategory();
				$object->id = -1;
				$object->title = 'Assets';
				$object->contents = $assets;
				$categories->push($object);
			}
			
			// Temporary category for anything else.
			$others = $uncategorized_contents->where('contentable_type', '!=', 'MorphAsset');
			if ($others) {
				$object = new stdClass(); // new AcCategory();
				$object->id = -2;
				$object->title = 'Uncategorized';
				$object->contents = $others;
				$categories->push($object);
			}
		}

		// Sort category contents in the order they were favorited.
		foreach($categories as &$category) {
			$category->contents = $category->contents->sortBy(function($content) use ($favorite_ids) {
				return array_search($content->id, $favorite_ids);
			});
		}
		unset($category);

		return view('frontend.ac.favorites')->with('categories', $categories);
	}

	public function add(Request $request, AcDomain $domain, AcContent $content) {
		$user = $request->user();

		if (AcFavorite::where('user_id', $user->id)->where('content_id', $content->id)->exists()) {
			return response()->json([
				'status' => 'invalid',
				'action' => 'add'
			]);
		}

		$favorite = new AcFavorite();
		$favorite->user_id = $user->id;
		$favorite->content_id = $content->id;

		if ($favorite->save()) {
			return response()->json([
				'status' => 'success',
				'action' => 'add'
			]);
		}

		return response()->json([
			'status' => 'error',
			'action' => 'add'
		], 500);
	}

	public function remove(Request $request, AcDomain $domain, AcContent $content) {
		$user = $request->user();
		$favorite = AcFavorite::where('user_id', $user->id)->where('content_id', $content->id)->first();

		if ($favorite == null) {
			return response()->json([
				'status' => 'invalid',
				'action' => 'remove'
			]);
		}

		if ($favorite->delete()) {
			return response()->json([
				'status' => 'success',
				'action' => 'remove'
			]);
		}

		return response()->json([
			'status' => 'error',
			'action' => 'remove'
		], 500);
	}

}
