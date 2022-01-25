<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Models\AcOrderItem;
use App\Models\AcStreams;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Jobs\UploadToS3;
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
use App\Models\AcContentUserMetric;
use App\Models\AcLiveSchedule;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use App\Services\Twitch\TwitchFacade as Twitch;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController.
 */
class HomeController extends Controller {

	/**
	 * @return \Illuminate\View\View
	 */
	public function index(AcDomain $domain) {
		// registered user cannot see the guest page anymore
		if (auth()->check()) {
			return redirect()->route('frontend.home');
		}

		$mentors = Role::with('users.account')->where('name', 'mentor')->first();
		$mentors->users = $mentors->users->filter(function($user) use ($domain) {
			return $user->account->ac_domain_id == $domain->id;
		});

		$contents = self::getGuestContents(); // yes, this loads 1 COTD for nothing
		$assets = AcContent::with(['contentable', 'categories'])
			//->whereHasIn('categories.domains', [ $domain->id ]) // assets do not have categories yet
			->where('contentable_type', 'MorphAsset')
			->displayOrder()
			->take(8)
			->get();

		return view('frontend.index')->with([
				'lastcontents' => $contents['videos'],
				'lastannouncements' => $contents['announcements'],
				'lastreviews' => $contents['reviews'],
				'mentors' => $mentors,
				'assets' => $assets
		]);
	}

	public function unsubscribe($subscriberID) {

		if (Auth::check()) {

			return redirect()->route('frontend.user.account');

		} else {
			$config = new \MailWizzApi_Config(array(
				'apiUrl'        => config('ac.MAILWIZZ_API_URL'),
				'publicKey'     => config('ac.MAILWIZZ_API_PUBLIC_KEY'),
				'privateKey'    => config('ac.MAILWIZZ_API_PRIVATE_KEY'),
			));
			$h = \MailWizzApi_Base::setConfig($config);
			$endpoint = new \MailWizzApi_Endpoint_ListSubscribers();
			
			// FETCH CUSTOM FIELDS
			$response = $endpoint->getSubscriber(config('ac.MAILWIZZ_LIST_ID'), $subscriberID);
			if(isset($response->body->toArray()['data']) && $response->body->toArray()['status'] == "success") {
				$mailwizz_record = $response->body->toArray()['data']['record'];
				$sub = explode(', ', $mailwizz_record['SUB']);
			} else {
				$mailwizz_record = null;
				$sub = null;
			}
			$sublist = explode(',', config('ac.MAILWIZZ_SUB_LIST'));


			if(isset($mailwizz_record['EMAIL'])) {
				// FETCH APPLICATION STATUS
				$client = new \GuzzleHttp\Client(['http_errors' => false]);
				$urlHub = config('app.env') == "production" ? "https://hub.agora.studio/wphook/agoracommunity" : "http://www.agora.studio.loc/wphook/agoracommunity";
				try {
					$response = $client->request('POST', $urlHub, [
						'headers' => [
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
						],
						'form_params' => [
							'key' => config('ac.AS_HUB_KEY'),
							'email' => $mailwizz_record['EMAIL']
						]
					]);
		
					// check if all is good (200)
					if(json_decode($response->getStatusCode()) == 200) {
						// status code (1 = new, 3, accepted, 4 = rejected, 0 = not found, -1 bad key, )
						$applicationStatus = json_decode($response->getBody()->getContents());
					} else {
						Mail::send(array(), array(), function ($message) {
							$message->to("patrick@agora.studio")
							->subject("AC to AS application guzzle KO")
							->from("patrick@agora.studio")
							->setBody("KO: " . $mailwizz_record['EMAIL'], 'text/html');
						});
						$applicationStatus = -1;
					}
		
				} catch(Exception $e) {
					Mail::send(array(), array(), function ($message) {
						$message->to("patrick@agora.studio")
						->subject("AC to AS application guzzle KO")
						->from("patrick@agora.studio")
						->setBody("KO: " . $mailwizz_record['EMAIL'], 'text/html');
					});
					$applicationStatus = -1;
				}
			} else {
				$applicationStatus = 0;
			}

			return view('frontend.unsubscribe')->with([
				'mailwizz_record' => $mailwizz_record,
				'sub' => $sub,
				'sublist' => $sublist,
				'applicationStatus' => $applicationStatus
			]);
		}


	}

	public function unsubscribePost(Request $request, $subscriberID) {

		 // newsletter
		 $config = new \MailWizzApi_Config(array(
            'apiUrl'        => config('ac.MAILWIZZ_API_URL'),
            'publicKey'     => config('ac.MAILWIZZ_API_PUBLIC_KEY'),
            'privateKey'    => config('ac.MAILWIZZ_API_PRIVATE_KEY'),
        ));
        $h = \MailWizzApi_Base::setConfig($config);
        $endpoint = new \MailWizzApi_Endpoint_ListSubscribers();
        $sid = $subscriberID;
		
		if(empty($request->sub)) {
			// UNSUBSCRIBE
			$response = $endpoint->update(config('ac.MAILWIZZ_LIST_ID'), $sid, array('SUB'   => ['']));
			if($response->body->toArray()['status'] != "success") {
				Mail::send(array(), array(), function ($message) {
					$message->to("patrick@agora.studio")
					->subject("Mailwizz API unsub KO")
					->from("patrick@agora.studio")
					->setBody("KO", 'text/html');
				});
			}
			return redirect()->route('frontend.unsubscribe', $sid)->withFlashSuccess('Successfully unsubscribed');
		} else {
			// UPDATE CUSTOM FIELD
			$response = $endpoint->update(config('ac.MAILWIZZ_LIST_ID'), $sid, array(
				'SUB'   => $request->sub
			));
			if($response->body->toArray()['status'] != "success") {
				Mail::send(array(), array(), function ($message) {
					$message->to("patrick@agora.studio")
					->subject("Mailwizz API unsub KO")
					->from("patrick@agora.studio")
					->setBody("KO", 'text/html');
				});
			}
			return redirect()->route('frontend.unsubscribe', $sid)->withFlashSuccess('Email preferences updated');
		}
	}
	

	/**
	 * GUEST PAGE for CONTENT
	 * @deprecated 2021-09-09
	 */
	public function content(Request $request, AcDomain $domain, $slug) {

		// fetch by the slug -- SECURITY isPublic must be 1
		$content = AcContent::with([
				'categories',
				'contentable',
				'tags' => function($query) use ($domain) { $query->where('domain_id', $domain->id); }
			])
			//->whereHasIn('categories.domains', [ $domain->id ])
			->where('slug', $slug)
			->where('isPublic', 1)
			->firstOrFail();

		// CREATE OR UPDATE PAGE VIEW METRICS
		if (auth()->check()) {
			$metric = AcContentUserMetric::firstOrNew(
					['user_id' => auth()->user()->id, 'content_id' => $content->id],
					['page_visits' => 0]);
			$metric->page_visits++;
			$metric->save();
		} else {
			$metric = null;
		}

		// FETCH ARTIST
		if ($content->contentable_type == "MorphReview") {
			$artist = AcAccount::find($content->contentable->artist_id);
		} else {
			$artist = null;
		}

		// COMMENT (HYVOR)
		$HYVOR_TALK_SSO_PRIVATE_KEY = config('ac.HYVOR_KEY');
		// empty object for guests
		$userData = [];
		if (auth()->check()) {
			// setup object for logged-in users
			$userData = [
				'id' => auth()->user()->id,
				'name' => auth()->user()->fullname,
				'email' => auth()->user()->email,
				'picture' => auth()->user()->picture,
				//'url' => $user->url (profile)
			];
		}
		// json and base64 encoding (used for hash and later)
		$encodedUserData = base64_encode(json_encode($userData));
		// creating the hash (will use it soon)
		$hash = hash_hmac('sha1', $encodedUserData, $HYVOR_TALK_SSO_PRIVATE_KEY);

		$similar = self::getSimilarContents($content);

		return view('frontend.content', compact(['content', 'similar', 'metric', 'artist', 'hash', 'encodedUserData']));
	}

	// ABOUT - accessible to all
	public function about() {

		$geoip = geoip()->getLocation();
		$currency = AcCurrency::where('iso', $geoip->currency)->first();
		$currency_id = !empty($currency->id) ? $currency->id : 1;

		$currencies = AcCurrency::all();
		//TODO refacto
		// we want the price for the reviews product family
		$f = AcProductFamily::where('acronym', 'reviews')->first();
		// we want to list all the price using the book prices
		// for each kind of product (live/pre -- public/private)
		// we put that in a array for now (using a MySQL VIEW todo)
		$prerecPublic = array();
		$prerecPrivate = array();
		$livePublic = array();
		$livePrivate = array();

		foreach ($f->prices->where('currency_id', $currency_id)->where('isActive', 1) as $price) {

			if (($price->product->isLive && $price->product->isPublic)) {
				$idasmin = substr($price->product->quantity, 0, -3);
				$livePublic[$idasmin] = [
					"id" => $price->id,
					"min" => $price->product->quantity,
					"description" => $price->product->description,
					"price" => $price->price,
					"currency" => $price->currency->name,
					"symbol" => $price->currency->symbol,
					"start_date" => $price->start_date,
					"end_date" => $price->end_date,
				];
				ksort($livePublic);
			} else if ($price->product->isLive && !$price->product->isPublic) {
				$idasmin = substr($price->product->quantity, 0, -3);
				$livePrivate[$idasmin] = [
					"id" => $price->id,
					"min" => $price->product->quantity,
					"description" => $price->product->description,
					"price" => $price->price,
					"currency" => $price->currency->name,
					"symbol" => $price->currency->symbol,
					"start_date" => $price->start_date,
					"end_date" => $price->end_date,
				];
				ksort($livePrivate);
			} else if (!$price->product->isLive && $price->product->isPublic) {
				$idasmin = substr($price->product->quantity, 0, -3);
				$prerecPublic[$idasmin] = [
					"id" => $price->id,
					"min" => $price->product->quantity,
					"description" => $price->product->description,
					"price" => $price->price,
					"currency" => $price->currency->name,
					"symbol" => $price->currency->symbol,
					"start_date" => $price->start_date,
					"end_date" => $price->end_date,
				];
				ksort($prerecPublic);
			} else if (!$price->product->isLive && !$price->product->isPublic) {
				$idasmin = substr($price->product->quantity, 0, -3);
				$prerecPrivate[$idasmin] = [
					"id" => $price->id,
					"min" => $price->product->quantity,
					"description" => $price->product->description,
					"price" => $price->price,
					"currency" => $price->currency->name,
					"symbol" => $price->currency->symbol,
					"start_date" => $price->start_date,
					"end_date" => $price->end_date,
				];
				ksort($prerecPrivate);
			}
		}

		$terms = AcTerm::latest('id')->first();

		return view('frontend.about', compact(['terms', 'currencies', 'currency_id', 'livePublic', 'livePrivate', 'prerecPublic', 'prerecPrivate']));
	}

	// LIVE! - accessible to all
	public function live(Request $request) {
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
		$archive = $category ? $category->contents()->exists() : false;

		return view('frontend.live', compact('archive', 'embed', 'exhibit', 'flipdown', 'ongoing', 'promote', 'schedules', 'streams', 'tz'));
	}

	public function liveInfiniteScrolling(Request $request) {
		$after = (string) $request->query('after', '');
		$before = (string) $request->query('before', '');
		$page = intval($request->query('page'), 10) ?: 1;
		$show = intval($request->query('show'), 10) ?: 10;

		$category = AcCategory::where('title', 'Live!')->with('contents.contentable')->firstOrFail();

		$html = '';

		foreach($category->contents->forPage($page, $show) as $content) {
			$with = [
				'content' => $content,
				'lazyload' => false,
				'login' => false
			];
			$html .= $before . view('frontend.includes.videoitem')->with($with)->render() . $after;
		}
		
		$total = $category->contents->count();
		$pages = ceil($total / $show);

		return response()->json(compact('html', 'page', 'pages', 'show', 'total'));
	}

	public function terms() {
		$terms = AcTerm::latest('id')->first();
		return view('frontend.terms', compact('terms'));
	}

	public function currencyAjax($currency, $expert) {

		$expertID = AcAccount::where('slug', $expert)->first();
		$f = AcProductFamily::where('acronym', 'reviews')->first();
		// we want to list all the price using the book prices
		// for each kind of product (live/pre -- public/private)
		// we put that in a array for now (using a MySQL VIEW todo)
		$prerecPublic = array();
		$prerecPrivate = array();
		$livePublic = array();
		$livePrivate = array();
		$streamPublic = array();
        $streamPrivate = array();
		
		$prices = $f->prices->where('currency_id', $currency)->where('account_id', $expertID->id)->where('isActive', 1);

		foreach ($prices as $price) {

			if ((!$price->product->isStream && $price->product->isLive && $price->product->isPublic)) {
				$idasmin = substr($price->product->quantity, 0, -3);
				$livePublic[$idasmin] = [
					"id" => $price->id,
					"min" => $price->product->quantity,
					"price" => $price->price,
					"start_date" => $price->start_date,
					"end_date" => $price->end_date,
				];
				ksort($livePublic);
			} else if (!$price->product->isStream && $price->product->isLive && !$price->product->isPublic) {
				$idasmin = substr($price->product->quantity, 0, -3);
				$livePrivate[$idasmin] = [
					"id" => $price->id,
					"min" => $price->product->quantity,
					"price" => $price->price,
					"start_date" => $price->start_date,
					"end_date" => $price->end_date,
				];
				ksort($livePrivate);
			} else if (!$price->product->isStream && !$price->product->isLive && $price->product->isPublic) {
				$idasmin = substr($price->product->quantity, 0, -3);
				$prerecPublic[$idasmin] = [
					"id" => $price->id,
					"min" => $price->product->quantity,
					"price" => $price->price,
					"start_date" => $price->start_date,
					"end_date" => $price->end_date,
				];
				ksort($prerecPublic);
			} else if (!$price->product->isStream && !$price->product->isLive && !$price->product->isPublic) {
				$idasmin = substr($price->product->quantity, 0, -3);
				$prerecPrivate[$idasmin] = [
					"id" => $price->id,
					"min" => $price->product->quantity,
					"price" => $price->price,
					"start_date" => $price->start_date,
					"end_date" => $price->end_date,
				];
				ksort($prerecPrivate);
			} else if($price->product->isStream && !$price->product->isLive && $price->product->isPublic) {
                $idasmin = substr($price->product->quantity,0,-3);
                $streamPublic[$idasmin] = [
                    "id" => $price->id,
                    "min" => $price->product->quantity,
                    "price" => $price->price,
                    "start_date" => $price->start_date,
                    "end_date" => $price->end_date,
                ];
                ksort($streamPublic);
            }else if($price->product->isStream && !$price->product->isLive && !$price->product->isPublic) {
                $idasmin = substr($price->product->quantity,0,-3);
                $streamPrivate[$idasmin] = [
                    "id" => $price->id,
                    "min" => $price->product->quantity,
                    "price" => $price->price,
                    "start_date" => $price->start_date,
                    "end_date" => $price->end_date,
                ];
                ksort($streamPrivate);
            }
		}

		$prerecPrivate = $prerecPrivate;

		return response()->json([array('prerecPrivate' => $prerecPrivate), array('prerecPublic' => $prerecPublic), array('livePrivate' => $livePrivate), array('livePublic' => $livePublic), array('streamPublic'=>$streamPublic), array('streamPrivate'=>$streamPrivate)], 200);
	}

	public function uploadFile(Request $request) {
		$headers = getallheaders();
		$vid = $request->file('file');
		$videoFile = $vid->getClientOriginalName();
		$videoFileMime = $vid->getMimeType();
		$videoFileSize = $vid->getSize();

		// from tmp to storage (file will be deleted by uploadtos3 queue)
		$destinationPath = storage_path() . '/uploads/';
		$newloc = $vid->move($destinationPath, $videoFile);

		// return the collected information to dropzone input hidden
		$metadata = array([
				'filename' => $videoFile,
				'mimeType' => $videoFileMime,
				'size' => $videoFileSize
		]);

		if (isset($headers['Uploadtype'])) {

			// ORDER for review
			if ($headers['Uploadtype'] == "review") {

				// GET FPS
				$process = new Process('ffprobe -v error -select_streams v -of default=noprint_wrappers=1:nokey=1 -show_entries stream=r_frame_rate ' . storage_path() . '/uploads/preview_' . $videoFile);
				$process->run();
				if (!$process->isSuccessful()) {
					$fps = null;
				} else {
					$framerate = $process->getOutput();
					$fps = eval('return ' . $framerate . ';');
				}
			} else if ($headers['Uploadtype'] == "video") {

				// GET FPS
				$process = new Process('ffprobe -v error -select_streams v -of default=noprint_wrappers=1:nokey=1 -show_entries stream=r_frame_rate ' . storage_path() . '/uploads/' . $videoFile);
				$process->run();
				if (!$process->isSuccessful()) {
					$fps = null;
				} else {
					$framerate = $process->getOutput();
					$fps = eval('return ' . $framerate . ';');
				}
				/*
				  // CREATE PREVIEW
				  $process = new Process('ffmpeg -y -i '.storage_path() . '/uploads/' . $videoFile.' -hide_banner -vf "scale=(iw*sar)*max(700/(iw*sar)\,480/ih):ih*max(700/(iw*sar)\,480/ih), crop=700:480" -c:v libx264 -crf 23 -maxrate 0.2M -bufsize 0.2M -movflags +faststart -an ' . storage_path() . '/uploads/preview_' . $videoFile);
				  $process->run();
				  if (!$process->isSuccessful()) {
				  $preview = null;
				  } else {
				  UploadToS3::dispatch("preview_" . $videoFile, $headers['Uploadpath']);
				  }
				 */
			}
		}

		$metadata = array([
				'filename' => $videoFile,
				'filepath' => rtrim($headers['Uploadpath'], '/') . '/' . $videoFile,
				'mimeType' => $videoFileMime,
				'size' => $videoFileSize,
				'fps' => isset($fps) ? $fps : 30
		]);

		// start queue uploading to S3 directory is in the custom header
		UploadToS3::dispatch($videoFile, $headers['Uploadpath']);

		return response()->json(['success' => $metadata]);
	}

	public function hyvor(Request $request) {
		if (empty($request->passcode)) {
			return response('', 403);
		}

		if (sha1(config('ac.HYVOR_APIKEY')) == $request->passcode) {

			$hyvor = json_decode($request->data);

			if ($request->type == "create") {
				$wh = new AcHyvor;
				$wh->type = $request->type;
				$wh->data = $request->data;
				$wh->hyvor_id = $hyvor->id;
				$wh->hyvor_page_id = $hyvor->page->id;
				$wh->markdown = $hyvor->markdown;
				$wh->parent_ids = $hyvor->parent_ids;
				$wh->depth = $hyvor->depth;
				$wh->posted_at = $hyvor->created_at;
				$wh->upvotes = $hyvor->upvotes;
				$wh->downvotes = $hyvor->downvotes;
				$wh->user_id = $hyvor->user->id;
				$wh->content_id = $hyvor->page->page_identifier;
				$wh->save();
			} else if ($request->type == "edit") {
				$wh = AcHyvor::where('hyvor_id', $hyvor->id)->firstOrFail();
				$wh->type = $request->type;
				$wh->data = $request->data;
				$wh->markdown = $hyvor->markdown;
				$wh->save();
			} else if ($request->type == "delete") {
				$wh = AcHyvor::where('hyvor_id', $hyvor->id)->firstOrFail();
				$wh->type = $request->type;
				$wh->save();
			}

			return response('Ok', 200)
					->header('Content-Type', 'text/plain');
		}
	}

	public static function getSimilarContents($original) {
		$domain = request()->domain;
		$domain_content_ids = $domain->getContentIds();
		
		$tags = $original->tags()
			->with(['contents' => function($query) use ($original, $domain_content_ids) {
				$query->with(['categories', 'contentable', 'contentable.mentor'])
					->whereIn('id', $domain_content_ids)
					->where('contentable_type', '!=', 'MorphAsset')
					->where('id', '!=', $original->id)
					->isAvailable();
			}])
			->where('domain_id', $domain->id)
			->get();

		$similar_content_ids = [];
		$similar_contents = [];
		
		foreach($tags as $tag) {
			$selected = $tag->contents->whereNotIn('id', $similar_content_ids)->take(2);
			$similar_content_ids = array_merge($similar_content_ids, $selected->pluck('id')->all());
			$similar_contents = array_merge($similar_contents, $selected->all());
		}

		return $similar_contents;
		// 4773qphc
	}

	public static function getGuestContents($get = ['*'], $with = ['contentable', 'categories']) {
		$domain = request()->domain;
		$id = app(AcContent::class)->getTable() . '.id';
		$in = $domain->getContentIds();

		// $announcements_category = $domain->categories()->where(app(AcCategory::class)->getTable().'.id', 19)->without('contents')->first();
		$announcements_category = $domain->announcements()->without('contents')->first();

		$announcements = $announcements_category ? $announcements_category->contents()->with($with)->get($get) : collect();
		$assets = AcContent::where('contentable_type', 'MorphAsset')->with($with)->displayOrder()->get($get);
		$cotd = AcContent::whereIn($id, $in)->where('cotd_start', '<=', now())->with($with)->orderBy('cotd_start', 'desc')->take(1)->get($get);
		$reviews = AcContent::whereIn($id, $in)->where('contentable_type', 'MorphReview')->with($with)->isAvailable()->displayOrder()->take(8)->get($get);
		$videos = AcContent::whereIn($id, $in)->where('contentable_type', 'MorphVideo')->with($with)->isAvailable()->displayOrder()->take(8)->get($get);

		return collect(compact('announcements', 'assets', 'cotd', 'reviews', 'videos'));
	}

	/**
	 * @deprecated 2021-09-09
	 */
	public static function getGuestContentIds() {
		return self::getGuestContents(['id'], [])->flatten(1)->pluck('id')->unique()->values()->all();
	}

}
