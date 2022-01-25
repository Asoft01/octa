<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Models\AcContent;
use App\Models\AcContentUserVote;
use App\Models\AcDomain;
use Illuminate\Http\Request;

class ContentUserVoteController extends Controller {

	const STATE_NEGATIVE = -1;
	const STATE_NEUTRAL = 0;
	const STATE_POSITIVE = 1;

	public function createOrUpdate(Request $request, AcDomain $domain) {
		$valid_states = [ self::STATE_NEGATIVE, self::STATE_NEUTRAL, self::STATE_POSITIVE ];

		abort_unless(AcContent::where('id', $request->post('content_id'))->exists(), 400);
		abort_unless(in_array($request->post('state', 2), $valid_states, true), 400);
		
		$vote = AcContentUserVote::firstOrNew([
			'user_id' => $request->user()->id,
			'content_id' => $request->post('content_id')
		]);
		$vote->state = $request->post('state');
		$vote->http_agent = $request->server('HTTP_USER_AGENT');
		$vote->ip_address = $request->ip();
		
		abort_unless($vote->save(), 500);
		
		$count = config('ac.SHOW_CONTENT_VOTE_COUNT') ? [
			'positive' => AcContentUserVote::where('content_id', $vote->content_id)->where('state', self::STATE_POSITIVE)->count(),
			'negative' => AcContentUserVote::where('content_id', $vote->content_id)->where('state', self::STATE_NEGATIVE)->count()
		] : null;
		
		return response()->json([
			'status' => 'success',
			'state' => $vote->state,
			'count' => $count,
		]);
	}
}
