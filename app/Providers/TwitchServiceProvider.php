<?php

namespace App\Providers;

use App\Services\Twitch\TwitchApi;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TwitchServiceProvider extends ServiceProvider implements DeferrableProvider {

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->singleton('twitch', function($app) {
			return new TwitchApi(config('services.twitch'));
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		// return [TwitchApi::class];
		return ['twitch'];
	}

}
