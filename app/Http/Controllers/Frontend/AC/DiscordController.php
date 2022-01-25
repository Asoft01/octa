<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class DiscordController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.ac.discord');
    }

}
