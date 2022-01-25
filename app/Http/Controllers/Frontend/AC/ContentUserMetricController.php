<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Models\AcContent;
use App\Models\AcContentUserMetric;
use Illuminate\Http\Request;

class ContentUserMetricController extends Controller {

	public function updateVideoTimes(Request $request) {
		abort_unless(is_numeric($request->post('playtime', 'abc')), 400);
		abort_unless(is_numeric($request->post('position', 'abc')), 400);
		abort_if($request->post('playtime') < -1, 400);
		abort_if($request->post('position') < -1, 400);

		$metric = AcContentUserMetric::findOrFail($request->post('metric_id'));
		$metric->video_playtime = intval($request->post('playtime')) + $metric->video_playtime;
		$metric->video_position = intval($request->post('position'));
		
		abort_unless($metric->save(), 500);
		
		return response()->json(['status' => 'success']);
	}

	public function updateAssetDownloads(Request $request) {
		abort_unless(AcContent::where('id', $request->post('content_id'))->where('contentable_type', 'MorphAsset')->exists(), 400);
		
		$metric = AcContentUserMetric::firstOrNew([
			'user_id' => $request->user()->id,
			'content_id' => $request->post('content_id')
		], [
			'page_visits' => 0
		]);
		$metric->asset_downloads = intval($metric->asset_downloads) + 1;

		abort_unless($metric->save(), 500);

		return response()->json(['status' => 'success']);
	}

}
