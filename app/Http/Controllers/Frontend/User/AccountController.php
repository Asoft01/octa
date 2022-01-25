<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

/**
 * Class AccountController.
 */
class AccountController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $config = new \MailWizzApi_Config(array(
            'apiUrl'        => config('ac.MAILWIZZ_API_URL'),
            'publicKey'     => config('ac.MAILWIZZ_API_PUBLIC_KEY'),
            'privateKey'    => config('ac.MAILWIZZ_API_PRIVATE_KEY'),
        ));
        $h = \MailWizzApi_Base::setConfig($config);
        $endpoint = new \MailWizzApi_Endpoint_ListSubscribers();
        
        // FIRST GET SUBSCRIBER ID FROM EMAIL
        $response = $endpoint->emailSearch(config('ac.MAILWIZZ_LIST_ID'), auth()->user()->email);
        if($response->body->toArray()['status'] == "success") {
            $sid = $response->body->toArray()['data']["subscriber_uid"];

            // FETCH CUSTOM FIELDS
            $response = $endpoint->getSubscriber(config('ac.MAILWIZZ_LIST_ID'), $sid);
            $mailwizz_record = $response->body->toArray()['data']['record'];

            // SUB
            $sub = explode(', ', $mailwizz_record['SUB']);
        } else {
            $mailwizz_record = null;
            $sub = null;
        }
        $sublist = explode(',', config('ac.MAILWIZZ_SUB_LIST'));

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
                    'email' => auth()->user()->email
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
                    ->setBody("KO: " . auth()->user()->email, 'text/html');
                });
                $applicationStatus = -1;
            }

        } catch(Exception $e) {
            Mail::send(array(), array(), function ($message) {
                $message->to("patrick@agora.studio")
                ->subject("AC to AS application guzzle KO")
                ->from("patrick@agora.studio")
                ->setBody("KO: " . auth()->user()->email, 'text/html');
            });
			$applicationStatus = -1;
        }

        return view('frontend.user.account', compact('mailwizz_record', 'sub', 'sublist', 'applicationStatus'));
    }

    public function editPublicInfo(Request $request) {
		$user = $request->user();
		$account = $user->account;
		$tz = $user->getTimezone();
		if ($user->account == null) { return abort(401); }
        return view('frontend.user.publicinfo', compact('account', 'tz', 'user'));
    }
	
	public function updatePublicInfo(Request $request) {
		// Exclude filepath inputs if they are empty so we don't insert them into the database.
		$input = array_diff_assoc($request->post(), [
			'poster'		=> null,
			'video'			=> null,
			//'preview_video'	=> null
		]);

		$user = $request->user();
		$user->load('account');

		if ($user->account == null) { return abort(401); }

		$validator = $this->makePublicInfoValidator($input, $user->hasRole('mentor'));
		$validator->validate();		
		$data = $validator->valid(); // The validator also ensures any unwanted fields are purged.

		// Convert dates from user timezone to app timezone.
		if (array_key_exists('bookeduntil', $data) && $data['bookeduntil'] != null) {
			$data['bookeduntil'] = Carbon::parse($data['bookeduntil'], $user->getTimezone())->setTimezone(config('app.timezone', 'UTC'));
		}

		$user->account->fill($data);
		$user->account->save();

		return redirect()->route('frontend.user.publicinfo.edit')->withSuccess('Public info updated.');
	}

	/**
	 * 
	 * @param array $input
	 * @param boolean $isMentor  [optional]
	 * @return Illuminate\Validation\Validator
	 */
	protected function makePublicInfoValidator($input, $isMentor = false) {
		$rules = [
			'position'		=> 'nullable|max:191',
			'cv'			=> 'nullable|string',
			'bio'			=> 'nullable|string',
			'poster'		=> 'sometimes|required|string',
			'video'			=> 'sometimes|required|string',
			//'preview_video'	=> 'sometimes|required|string'
		];
		if ($isMentor == true) {
			$rules += [
				'hoursWeek'		=> 'required|integer',
				'bookeduntil'	=> 'nullable|date',
				'delay'			=> 'nullable|integer',
			];
		}
		return Validator::make(array_intersect_key($input, $rules), $rules);
	}
}
