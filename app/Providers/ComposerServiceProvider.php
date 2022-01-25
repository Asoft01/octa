<?php

namespace App\Providers;

use App\Http\Composers\Backend\SidebarComposer;
use App\Http\Composers\Frontend\NavComposer;
use App\Http\Composers\GlobalComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Class ComposerServiceProvider.
 */
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot()
    {
        // Global
        View::composer(
            // This class binds the $logged_in_user variable to every view
            '*',
            GlobalComposer::class
        );

        // Frontend
        View::composer(
            // This class binds the $logged_in_user variable to every view
            ['frontend.includes.nav', 'frontend.ac.contents'],
            NavComposer::class
        );

        // Backend
        View::composer(
            // This binds items like number of users pending approval when account approval is set to true
            'backend.includes.sidebar',
            SidebarComposer::class
        );
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
