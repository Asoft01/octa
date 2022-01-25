<?php

namespace App\Services\Twitch;

use Illuminate\Support\Facades\Facade;

class TwitchFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'twitch';
	}

}
