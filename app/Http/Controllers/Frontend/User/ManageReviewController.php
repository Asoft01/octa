<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

use App\Models\AcDelivery;
use App\Models\AcOrder;
use App\Models\AcOrderItem;
use App\Models\AcAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

/**
 * Class AccountController.
 */
class ManageReviewController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(auth()->user()->hasRole('mentor')) {
            $deliveries = auth()->user()->account->deliveries;
        } else {
            $deliveries = auth()->user()->deliveries;
        }

        return view('frontend.user.manage', compact('deliveries'));
    }

    public function wiki()
    {
        if(!auth()->user()->hasRole('mentor')) {
            abort(403, 'Unauthorized action.');
        }

        // cockpit fetch page
		$client = new Client(['http_errors' => false]);
		$filter = [\GuzzleHttp\RequestOptions::JSON => [
			'filter' => ['name' => 'expertwiki']],
			'headers' => ['Cockpit-Token' => config('ac.HEADLESSCMSTOKEN')]
		];
		$r = $client->request('POST', config('ac.HEADLESSCMSDNS').'/api/collections/get/agoracommunity',$filter)->getBody()->getContents();
		$page = json_decode($r)->entries[0];
		$content = isset($page->content) ? $page->content : null;

        return view('frontend.user.expertwiki', compact('content'));
    }

    public function review($uuid)
    {
        $order = AcOrder::where('uuid', $uuid)->firstOrFail();
        $oi = $order->orderitems->first();
        $delivery = $oi->delivery;

        return view('frontend.user.review', compact('delivery'));
    }

    public function availabilityAjax($reviewer)
    {
        $reviewer = AcAccount::where('slug', $reviewer)->first();
        $nbtodo = AcDelivery::where('reviewer_id', $reviewer->id)->where('status_id', 2)->sum('quantity_sold');
        $duedatefloat = floatval($nbtodo) / $reviewer->hoursWeek + ($reviewer->delay / 5); // helping the expert, not counting the week-end / 5

      //dd("nbtoto:".$nbtodo."----hoursperweek:".$reviewer->hoursWeek."------delay:".$reviewer->delay."::::::::::;duedate:".$duedatefloat);
    
        if($duedatefloat >= 0 && $duedatefloat < 1) {
            $duecalculated = "Within a week";
        } else if($duedatefloat >= 1 && $duedatefloat < 2) {
            $duecalculated = "Within 2 weeks";
        } else if($duedatefloat >= 2 && $duedatefloat < 3) {
            $duecalculated = "Within 3 weeks";
        } else if($duedatefloat >= 3 && $duedatefloat < 4) {
            $duecalculated = "Within 4 weeks";
        } else if($duedatefloat >= 4 && $duedatefloat < 8) {
            $duecalculated = "Next month";
        } else if($duedatefloat >= 8 && $duedatefloat < 12) {
            $duecalculated = "In 2 months";
        } else if($duedatefloat >= 12 && $duedatefloat < 16) {
            $duecalculated = "In 3 months";
        } else if($duedatefloat >= 16 && $duedatefloat < 20) {
            $duecalculated = "In 4 months";                                                                
        } else if($duedatefloat >= 20 && $duedatefloat < 24) {
            $duecalculated = "In 5 months";
        } else if($duedatefloat >= 24 && $duedatefloat < 28) {
            $duecalculated = "In 6 months";
        } else if($duedatefloat >= 28) {
            $duecalculated = "More than 6 months...";
        }

        $msg = $duecalculated;
        return response()->json(array('msg'=> $msg), 200);
    }
}
