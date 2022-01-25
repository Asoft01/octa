<?php

namespace App\Listeners\Frontend\Auth;

use App\Events\Frontend\Auth\UserConfirmed;
use App\Events\Frontend\Auth\UserLoggedIn;
use App\Events\Frontend\Auth\UserLoggedOut;
use App\Events\Frontend\Auth\UserProviderRegistered;
use App\Events\Frontend\Auth\UserRegistered;

/**
 * Class UserEventListener.
 */
class UserEventListener
{
    /**
     * @param $event
     */
    public function onLoggedIn($event)
    {
        $ip_address = request()->getClientIp();

        // Update the logging in users time & IP
        $event->user->fill([
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => $ip_address,
        ]);

        // Update the timezone via IP address
        $geoip = geoip($ip_address);

        if ($event->user->timezone !== $geoip['timezone']) {
            // Update the users timezone
            $event->user->fill([
                'timezone' => $geoip['timezone'],
            ]);
        }

        $event->user->save();

        logger('User Logged In: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onLoggedOut($event)
    {
        logger('User Logged Out: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onRegistered($event)
    {
        logger('User Registered: '.$event->user->full_name);
    }

    // refactor single call
    /**
     * @param $event
     */
    public function onProviderRegistered($event)
    {
        logger('User Provider Registered: '.$event->user->full_name);

         // ADD TO MAILWIZZ
         try {
			$config = new \MailWizzApi_Config(array(
				'apiUrl'        => config('ac.MAILWIZZ_API_URL'),
				'publicKey'     => config('ac.MAILWIZZ_API_PUBLIC_KEY'),
				'privateKey'    => config('ac.MAILWIZZ_API_PRIVATE_KEY'),
			));
			$h = \MailWizzApi_Base::setConfig($config);
			$endpoint = new \MailWizzApi_Endpoint_ListSubscribers();
			$response = $endpoint->create(config('ac.MAILWIZZ_LIST_ID'), array(
				'EMAIL'    => $event->user->email,
				'FNAME'    => $event->user->first_name,
                'LNAME'    => $event->user->last_name,
                'MEMBER_SINCE'    => date('Y-m-d H:i:s'),
                'IP_ADDRESS'	=> \Request::ip(),
                'SUB'   => explode(',', config('ac.MAILWIZZ_SUB_LIST'))
			));
			if($response->status == "error") {
				throw new Exception();
			}
		} catch (\Exception $e) {
			$html = "<p>Mailwizz add email error</p>";
			Mail::send(array(), array(), function ($message) use ($html) {
				$message->to("patrick@agora.studio")
				->subject("AC - Mailwizz error")
				->from("patrick@agora.studio")
				->setBody($html, 'text/html');
			});
		}
    }

    /**
     * @param $event
     */
    public function onConfirmed($event)
    {
        logger('User Confirmed: '.$event->user->full_name);

        // ADD TO MAILWIZZ
        try {
			$config = new \MailWizzApi_Config(array(
				'apiUrl'        => config('ac.MAILWIZZ_API_URL'),
				'publicKey'     => config('ac.MAILWIZZ_API_PUBLIC_KEY'),
				'privateKey'    => config('ac.MAILWIZZ_API_PRIVATE_KEY'),
			));
			$h = \MailWizzApi_Base::setConfig($config);
			$endpoint = new \MailWizzApi_Endpoint_ListSubscribers();
			$response = $endpoint->create(config('ac.MAILWIZZ_LIST_ID'), array(
				'EMAIL'    => $event->user->email,
				'FNAME'    => $event->user->first_name,
                'LNAME'    => $event->user->last_name,
                'MEMBER_SINCE'    => date('Y-m-d H:i:s'),
                'IP_ADDRESS'	=> \Request::ip(),
                'SUB'   => explode(',', config('ac.MAILWIZZ_SUB_LIST'))
			));
			if($response->status == "error") {
				throw new Exception();
			}
		} catch (\Exception $e) {
			$html = "<p>Mailwizz add email error</p>";
			Mail::send(array(), array(), function ($message) use ($html) {
				$message->to("patrick@agora.studio")
				->subject("AC - Mailwizz error")
				->from("patrick@agora.studio")
				->setBody($html, 'text/html');
			});
		}
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserLoggedIn::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onLoggedIn'
        );

        $events->listen(
            UserLoggedOut::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onLoggedOut'
        );

        $events->listen(
            UserRegistered::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onRegistered'
        );

        $events->listen(
            UserProviderRegistered::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onProviderRegistered'
        );

        $events->listen(
            UserConfirmed::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onConfirmed'
        );
    }
}
