<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Sets third party service providers that are only needed on local/testing environments
        if ($this->app->environment() !== 'production') {
            /**
             * Loader for registering facades.
             */
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();

            // Load third party local aliases
            $loader->alias('Debugbar', \Barryvdh\Debugbar\Facade::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        /*
         * Application locale defaults for various components
         *
         * These will be overridden by LocaleMiddleware if the session local is set
         */

        // setLocale for php. Enables ->formatLocalized() with localized values for dates
        setlocale(LC_TIME, config('app.locale_php'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized
        Carbon::setLocale(config('app.locale'));

        /*
         * Set the session variable for whether or not the app is using RTL support
         * For use in the blade directive in BladeServiceProvider
         */
        if (! app()->runningInConsole()) {
            if (config('locale.languages')[config('app.locale')][2]) {
                session(['lang-rtl' => true]);
            } else {
                session()->forget('lang-rtl');
            }
        }

        // Force SSL in production
        /*if ($this->app->environment() === 'production') {
            URL::forceScheme('https');
        }*/

        // Set the default template for Pagination to use the included Bootstrap 4 template
        \Illuminate\Pagination\AbstractPaginator::defaultView('pagination::bootstrap-4');
        \Illuminate\Pagination\AbstractPaginator::defaultSimpleView('pagination::simple-bootstrap-4');

        // Custom Blade Directives

        /*
         * The block of code inside this directive indicates
         * the project is currently running in read only mode.
         */
        Blade::if('readonly', function () {
            return config('app.read_only');
        });

        /*
         * The block of code inside this directive indicates
         * the chosen language requests RTL support.
         */
        Blade::if('langrtl', function ($session_identifier = 'lang-rtl') {
            return session()->has($session_identifier);
        });

		/*
		 * The @render directive is similar to the @include directive.
		 * However, variables that are available to the parent view
		 * will NOT be made available to the subview.
		 */
		Blade::directive('render', function ($expression) {
			return "<?php echo \$__env->make({$expression})->render(); ?>";
		});

        Collection::macro('sortDesc', function($callback = null) {
            return $this->sort($callback)->reverse();
        });

        Collection::macro('normalSort', function($flags = SORT_NATURAL|SORT_FLAG_CASE) {
            return $this->sortBy(function($item) {
                return Str::ascii($item);
            }, $flags);
        });
        
        Collection::macro('normalSortBy', function($attribute, $flags = SORT_NATURAL|SORT_FLAG_CASE) {
            return $this->sortBy(function($item) use ($attribute) {
                return Str::ascii($item->{$attribute});
            }, $flags);
        });

    }
}
