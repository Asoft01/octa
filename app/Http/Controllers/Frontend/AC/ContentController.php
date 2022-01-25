<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\HomeController;
use App\Models\AcOrderItem;
use App\Models\AcStreams;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use App\Models\AcCategory;
use App\Models\AcContent;
use App\Models\AcDomain;
use App\Models\AcTag;
use App\Models\AcReview;
use App\Models\AcAsset;
use App\Models\AcLive;
use App\Models\AcAccount;
use App\Models\AcHyvor;
use App\Models\AcPrice;
use App\Models\AcProduct;
use App\Models\AcProductFamily;
use App\Models\AcUnit;
use App\Models\AcLanguage;
use App\Models\AcCurrency;
use App\Models\AcTerm;
use App\Models\AcLiveSchedule;

use App\Models\AcContentUserMetric;
use App\Models\AcPlaylist;

class ContentController extends Controller {

	public function show(Request $request, AcDomain $domain, $slug) {
		$content = $this->getContentOrFail($slug, $domain);
		$user = $request->user();

		if ($content->contentable_type == 'MorphPlaylist') {
			return redirect()->route('frontend.playlist.show', $content->slug);
		}

		if ($user) {
			// Authenticated user. Create or update metrics and prepare $userData.
			$metric = $this->createOrUpdateMetrics($user, $content);
			$userData = $user->only(['id', 'name', 'email', 'picture']);
		} else {
			// Guest user on freely available content. Initialize empty variables.
			$metric = null;
			$userData = [];
		}

		// Fetch artist.
		if ($content->contentable_type == 'MorphReview') {
			$artist = AcAccount::find($content->contentable->artist_id);
		} else {
			$artist = null;
		}

		// Fetch playlist.
		$playlist = $this->getPlaylist($request->query('playlist'), optional($user)->id);

		if ($playlist) {
			$playlist->current = $content;
			$similar = null;
		} else {
			// Fetch similar content.
			$similar = HomeController::getSimilarContents($content);
		}

		// Prepare comments.
		$encodedUserData = base64_encode(json_encode($userData)); // json and base64 encoding (used for hash and later)
		$hash = hash_hmac('sha1', $encodedUserData, config('ac.HYVOR_KEY')); // creating the hash (will use it soon)

		// Return view.
		return view('frontend.content', compact(['content', 'similar', 'metric', 'artist', 'hash', 'encodedUserData', 'playlist']));
	}

	public function showPlaylist(Request $request, AcDomain $domain, $slug) {
		$user = $request->user();
		$playlist = $this->getPlaylist($slug, optional($user)->id);
		abort_unless($playlist, 404);

		$playlist->contentable->sortContents();

		if ($user) {
			// Authenticated user. Create or update metrics and prepare $userData.
			$metric = $this->createOrUpdateMetrics($user, $playlist);
			$userData = $user->only(['id', 'name', 'email', 'picture']);
		}
		else {
			// Guest user on freely available content. Initialize empty variables.
			$metric = null;
			$userData = [];
		}

		$artist = null;
		$content = $playlist;
		$similar = null;

		// Prepare comments.
		$encodedUserData = base64_encode(json_encode($userData)); // json and base64 encoding (used for hash and later)
		$hash = hash_hmac('sha1', $encodedUserData, config('ac.HYVOR_KEY')); // creating the hash (will use it soon)

		// Return view.
		return view('frontend.content', compact(['content', 'similar', 'metric', 'artist', 'hash', 'encodedUserData', 'playlist']));
	}

	protected function createOrUpdateMetrics($user, $content) {
		$model = AcContentUserMetric::firstOrNew(
			['user_id' => $user->id, 'content_id' => $content->id],
			['page_visits' => 0]
		);
		$model->page_visits++;
		$model->save();
		return $model;
	}

	protected function getContentOrFail($slug, $domain) {
		return AcContent::with([
			'categories',
			'contentable',
			'tags' => function($query) use ($domain) {
				$query->where('domain_id', $domain->id);
			}
		])
		//->whereHasIn('categories.domains', [ $domain->id ])
		->where('slug', $slug)
		->where('isPublic', 1)
		->firstOrFail();
	}

	protected function getPlaylist($slug, $user_id = null) {
		if ($slug == null) return false;

		$model = AcContent::with([
				'categories',
				'contentable.contents',
				'contentable.contents.metrics' => function($q) use ($user_id) { $q->where('user_id', $user_id); },
				'contentable.contents.votes'   => function($q) use ($user_id) { $q->where('user_id', $user_id); },
				'contentable.mentor',
				//'metrics' => function($query) use ($user_id) { $query->where('user_id', $user_id); },
				//'votes'   => function($query) use ($user_id) { $query->where('user_id', $user_id); },
			])
			->where('contentable_type', 'MorphPlaylist')
			->where('slug', $slug)
			->isAvailable()
			->displayOrder()
			->first();
		
		if ($model == null) return null;

		// Ensure we can use $model->contentable->user without a redundant database query.
		if ($model->contentable->mentor) {
			$model->contentable->setRelation('user', $model->contentable->mentor->user);
		} else {
			$model->contentable->load('user');
		}

		return $model;
	}
}
