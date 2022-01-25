<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
// use App\Models\AcCategory;
use App\Models\AcContent;
use App\Models\AcDomain;
use App\Models\AcWatchlistItem;
use Illuminate\Http\Request;
// use stdClass;

class WatchlistItemController extends Controller {

	public function index(Request $request, AcDomain $domain) {
		$contents = $request->user()->watchlist()->latest()->with(['content' => function($query) {
			$query->where('display_start', '<=', now())->where('isPublic', 1);
		}, 'content.contentable', 'content.categories'])->get()->transform(function($favorite) {
			return $favorite->content;
		});
		return view('frontend.ac.watchlist')->with('contents', $contents);
	}

	public function add(Request $request, AcDomain $domain, AcContent $content) {
		$user = $request->user();

		if (AcWatchlistItem::where('user_id', $user->id)->where('content_id', $content->id)->exists()) {
			return response()->json([
				'status' => 'invalid',
				'action' => 'add'
			]);
		}

		$wlitem = new AcWatchlistItem();
		$wlitem->user_id = $user->id;
		$wlitem->content_id = $content->id;

		if ($wlitem->save()) {
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
		$wlitem = AcWatchlistItem::where('user_id', $user->id)->where('content_id', $content->id)->first();

		if ($wlitem == null) {
			return response()->json([
				'status' => 'invalid',
				'action' => 'remove'
			]);
		}

		if ($wlitem->delete()) {
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
