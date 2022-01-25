<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Auth\UserRepository;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
    {
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('first_name', 'last_name', 'email', 'avatar_type', 'avatar_location'),
            $request->has('avatar_location') ? $request->file('avatar_location') : false
        );

        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            auth()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(__('strings.frontend.user.email_changed_notice'));
        }

        // newsletter
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

            // UNSUBSCRIBE
            if(empty($request->sub)) {
                $response = $endpoint->update(config('ac.MAILWIZZ_LIST_ID'), $sid, array('SUB'   => ['']));
                //$response = $endpoint->unsubscribe(config('ac.MAILWIZZ_LIST_ID'), $sid);
                if($response->body->toArray()['status'] != "success") {
                    Mail::send(array(), array(), function ($message) {
                        $message->to("patrick@agora.studio")
                        ->subject("Mailwizz API unsub KO")
                        ->from("patrick@agora.studio")
                        ->setBody("KO", 'text/html');
                    });
                }
            } else {
                // UPDATE CUSTOM FIELD
                $response = $endpoint->update(config('ac.MAILWIZZ_LIST_ID'), $sid, array(
                    'SUB'   => $request->sub
                ));
                //$response = $endpoint->subscribe(config('ac.MAILWIZZ_LIST_ID'), $sid);
                if($response->body->toArray()['status'] != "success") {
                    Mail::send(array(), array(), function ($message) {
                        $message->to("patrick@agora.studio")
                        ->subject("Mailwizz API unsub KO")
                        ->from("patrick@agora.studio")
                        ->setBody("KO", 'text/html');
                    });
                }
            }
        } else {
            $mailwizz_record = null;
            $sub = null;
        }

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }

}
