<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Models\AcCategory;
use App\Models\AcLive;
use App\Models\AcLiveSchedule;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
	public function challenge(){
        
        return view('frontend.challenge');
    }	 
    public function vicChallenge(Request $request) {
		$streams = AcLive::orderBy('isStreaming', 'desc')->orderBy('user_login', 'asc')->get();
		
		$exhibit_stream_key = $streams->where('user_login', config('services.twitch.user_login'))->keys()->first();
		if ($exhibit_stream_key) {
			$exhibit = $streams->pull($exhibit_stream_key);
			$online = $exhibit->isStreaming;
			$streams->prepend($exhibit);
		} else {
			$exhibit = null;
			$online = false;
		}

		$now = now();
		$tz = optional($request->user())->timezone ?: config('app.timezone', 'UTC');

		$schedules_all = AcLiveSchedule::orderBy('eventDatetime')->get();
		$schedules_ongoing = $schedules_all->where('started_at', '<=', $now)->where('ended_at', '>=', $now);
		$schedules_upcoming = $schedules_all->where('started_at', '>', $now);
		
		if ($schedules_ongoing->isNotEmpty()) {
			$embed = $exhibit || $streams->contains('isStreaming', 1);
			$ongoing = $schedules_ongoing->first();
			$promote = $ongoing;
			$flipdown = false;
		} else {
			$embed = $streams->contains('isStreaming', 1);
			$ongoing = null;
			$promote = !optional($exhibit)->isStreaming ? $schedules_upcoming->first() : null;
			$flipdown = boolval($promote);
		}

		$schedules = $schedules_ongoing->merge($schedules_upcoming);

		// Get previous streams from the "Live" category.
		$category = AcCategory::where('title', 'Live!')->first();
		// $archive = $category ? $category->contents()->exists() : false;

		return view('frontend.challenge-vic', compact('embed', 'exhibit', 'flipdown', 'ongoing', 'schedules', 'streams', 'tz'));
	}  
}
