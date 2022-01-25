<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Events\Frontend\Auth\UserLoggedIn;
use App\Events\Frontend\Auth\UserLoggedOut;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Mail;

/**
 * Class LoginController.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function credentials(Request $request)
    {
        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $field => $request->username,
            'password' => $request->password,
        ];
        return $credentials;
        //return $request->only($this->username(), 'password');
    }


    // CUSTOM for redirecting to the login page if error (overwriting the default)
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ])->redirectTo('login');
    }


    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(home_route());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return config('access.users.username');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => PasswordRules::login(),
            'g-recaptcha-response' => ['required_if:captcha_status,true', 'captcha'],
        ], [
            'g-recaptcha-response.required_if' => __('validation.required', ['attribute' => 'captcha']),
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param         $user
     *
     * @throws GeneralException
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {

        // Check to see if the users account is confirmed and active
        if (! $user->isConfirmed()) {
            auth()->logout();

            // If the user is pending (account approval is on)
            if ($user->isPending()) {
                throw new GeneralException(__('exceptions.frontend.auth.confirmation.pending'));
            }

            // Otherwise see if they want to resent the confirmation e-mail

            throw new GeneralException(__('exceptions.frontend.auth.confirmation.resend', ['url' => route('frontend.auth.account.confirm.resend', e($user->{$user->getUuidName()}))]));
        }

        if (! $user->isActive()) {
            auth()->logout();

            throw new GeneralException(__('exceptions.frontend.auth.deactivated'));
        }

        event(new UserLoggedIn($user));

        if (config('access.users.single_login')) {
            auth()->logoutOtherDevices($request->password);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Remove the socialite session variable if exists
        if (app('session')->has(config('access.socialite_session_name'))) {
            app('session')->forget(config('access.socialite_session_name'));
        }

        // Fire event, Log out user, Redirect
        event(new UserLoggedOut($request->user()));

        // Laravel specific logic
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect()->route('frontend.index');
    }

    public function delete(Request $request)
    {
        $user = $request->user();
        // Delete associated relationships
        if(count($user->comments)) {
            // TODO HYVOR EMAIL SSO TO REMOVE THE ACCOUNT - it will be eventually a API CALL (wip)
            $html = "<p>Email: ".$user->email."<br />Website ID: 2362</p>";
            Mail::send(array(), array(), function ($message) use ($html) {
                $message->to(config('app.env') == "production" ? "talk.support@hyvor.com" : "patrick@agora.studio")
                ->subject("Deleting SSO User Data")
                ->from("patrick@agora.studio")
                ->setBody($html, 'text/html');
            });
        }
        $user->comments()->delete(); // not deleting at hyvor.....
        $user->favorites()->delete();
        $user->watchlist()->delete();
        $user->votes()->delete();
        $user->metrics()->delete();
        $user->passwordHistories()->delete();
        $user->providers()->delete();
        $user->forceDelete();

        // MAILWIZZ delete
        $config = new \MailWizzApi_Config(array(
            'apiUrl'        => config('ac.MAILWIZZ_API_URL'),
            'publicKey'     => config('ac.MAILWIZZ_API_PUBLIC_KEY'),
            'privateKey'    => config('ac.MAILWIZZ_API_PRIVATE_KEY'),
        ));
        $h = \MailWizzApi_Base::setConfig($config);
        $endpoint = new \MailWizzApi_Endpoint_ListSubscribers();
        $response = $endpoint->deleteByEmail(config('ac.MAILWIZZ_LIST_ID'), $user->email);
        if($response->status == "error") {
            Mail::send(array(), array(), function ($message) use ($html) {
                $message->to("patrick@agora.studio")
                ->subject("Error delete mailwizz email")
                ->from("patrick@agora.studio")
                ->setBody("KO", 'text/html');
            });
        }

        // Remove the socialite session variable if exists
        if (app('session')->has(config('access.socialite_session_name'))) {
            app('session')->forget(config('access.socialite_session_name'));
        }

        // Fire event, Log out user, Redirect
        event(new UserLoggedOut($request->user()));

        // Laravel specific logic
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect()->route('frontend.index');
    }
}
