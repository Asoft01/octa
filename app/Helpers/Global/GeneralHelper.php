<?php

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend')) {
                //return 'admin.dashboard';
                return 'frontend.home';
            }

            return 'frontend.home';
        }

        return 'frontend.index';
    }
}

if (! function_exists('build_url')) {
	/**
     * Generate URL from its components (i.e., opposite of built-in php function, parse_url()).
	 * Modified from robocoder's original gist:
	 *  - The query string does not need to be rebuilt;
	 *  - The URL fragment belongs after the query.
     *
	 * @link https://gist.github.com/robocoder/33afa327be2838e83b13d6ddbc996c29
     * @param array $components
     * @return string
     */
    function build_url($components) {
        $url = $components['scheme'] . '://';

        if ( ! empty($components['username']) && ! empty($components['password'])) {
            $url .= $components['username'] . ':' . $components['password'] . '@';
        }

        $url .= $components['host'];

        if ( ! empty($components['port']) &&
            (($components['scheme'] === 'http' && $components['port'] !== 80) ||
            ($components['scheme'] === 'https' && $components['port'] !== 443))
        ) {
            $url .= ':' . $components['port'];
        }

        if ( ! empty($components['path'])) {
            $url .= $components['path'];
        }

        if ( ! empty($components['query'])) {
            $url .= '?' . $components['query'];
        }

		if ( ! empty($components['fragment'])) {
            $url .= '#' . $components['fragment'];
        }

        return $url;
    }
}

if (! function_exists('is_model')) {
	/**
	 * Determines if $object is an Eloquent Model.
	 * 
	 * @param mixed $object
	 * @return boolean
	 */
	function is_model($object) {
		return is_subclass_of($object, 'Illuminate\Database\Eloquent\Model');
	}
}