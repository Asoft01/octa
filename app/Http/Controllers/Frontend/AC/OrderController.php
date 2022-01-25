<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Helpers\General\ZoomHelper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

use App\Models\AcAccount;

use App\Models\AcPrice;
use App\Models\AcProduct;
use App\Models\AcProductFamily;
use App\Models\AcUnit;
use App\Models\AcLanguage;

use App\Models\AcCurrency;
use App\Models\AcOrder;
use App\Models\AcOrderItem;
use App\Models\AcStreams;
use Ramsey\Uuid\Uuid;

use App\Models\AcDelivery;
use App\Models\AcPayment;

use App\Models\AcTerm;


use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrderReviewToUser;
use App\Mail\NewOrderReviewToMentor;
use App\Mail\NewOrderReviewToAgora;

use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Carbon\CarbonInterface;

use Taxman\Taxes;
use GeoIP as GeoIP;

use Asana\Client;

/**
 * Class DashboardController.
 */
class OrderController extends Controller
{


    //----------------------------------------------------------------
    // STEP 1 - OPTIONS
    //----------------------------------------------------------------
    public function index(Request $request)
    {

        // So user is coming from the reviewer page, clean old session if any so that the droplist is selecting the right reviewer
        if ($request->has('order_uuid')) {
            session()->forget('order_uuid');
            session()->forget('orderdate');
        }

        // ORDER ALREADY EXISTING (in session) sending values
        if(session()->exists('order_uuid')) {
            $order = AcOrder::where('uuid', session('order_uuid'))->firstOrFail();
            $oi = $order->orderitems->first();
            $reviewer_id = $oi->reviewer_id;
            $currency_id = $oi->currency_id;
            $price_id = $oi->price_id;
        } else {
            // fetch geoip and use that as default
            $geoip = geoip()->getLocation();
            $currency = AcCurrency::where('iso', $geoip->currency)->first();

            $reviewer_id = null;
            $currency_id = !empty($currency->id) ? $currency->id : 1;
            $price_id = null;
        }

        $currencies = AcCurrency::all();
        //TODO refacto
        // we want the price for the reviews product family
        $f = AcProductFamily::where('acronym', 'reviews')->first();
        // we want to list all the price using the book prices
        // for each kind of product (live/pre -- public/private)
        // we put that in a array for now (using a MySQL VIEW todo)
        $prerecPublic = array();
        $prerecPrivate = array();
        $livePublic = array();
        $livePrivate = array();
        $streamPublic = array();
        $streamPrivate=array();  

        $prices = $f->prices->where('currency_id', $currency_id)->where('account_id', null)->where('isActive', 1);

        foreach($prices as $price) {

            if((!$price->product->isStream && $price->product->isLive && $price->product->isPublic)) {
                $idasmin = substr($price->product->quantity,0,-3);
                $livePublic[$idasmin] = [
                    "id" => $price->id,
                    "min" => $price->product->quantity,
                    "description" => $price->product->description,
                    "price" => $price->price,
                    "currency" => $price->currency->name,
                    "symbol" => $price->currency->symbol,
                    "start_date" => $price->start_date,
                    "end_date" => $price->end_date,
                ];
                ksort($livePublic);
            } else if(!$price->product->isStream && $price->product->isLive && !$price->product->isPublic) {
                $idasmin = substr($price->product->quantity,0,-3);
                $livePrivate[$idasmin] = [
                    "id" => $price->id,
                    "min" => $price->product->quantity,
                    "description" => $price->product->description,
                    "price" => $price->price,
                    "currency" => $price->currency->name,
                    "symbol" => $price->currency->symbol,
                    "start_date" => $price->start_date,
                    "end_date" => $price->end_date,
                ];
                ksort($livePrivate);
            } else if(!$price->product->isStream && !$price->product->isLive && $price->product->isPublic) {
                $idasmin = substr($price->product->quantity,0,-3);
                $prerecPublic[$idasmin] = [
                    "id" => $price->id,
                    "min" => $price->product->quantity,
                    "description" => $price->product->description,
                    "price" => $price->price,
                    "currency" => $price->currency->name,
                    "symbol" => $price->currency->symbol,
                    "start_date" => $price->start_date,
                    "end_date" => $price->end_date,
                ];
                ksort($prerecPublic);
            } else if(!$price->product->isStream && !$price->product->isLive && !$price->product->isPublic) {
                $idasmin = substr($price->product->quantity,0,-3);
                $prerecPrivate[$idasmin] = [
                    "id" => $price->id,
                    "min" => $price->product->quantity,
                    "description" => $price->product->description,
                    "price" => $price->price,
                    "currency" => $price->currency->name,
                    "symbol" => $price->currency->symbol,
                    "start_date" => $price->start_date,
                    "end_date" => $price->end_date,
                ];
                ksort($prerecPrivate);
            } else if(!$price->product->isLive && $price->product->isPublic && $price->product->isStream) {
                $idasmin = substr($price->product->quantity,0,-3);
                $streamPublic[$idasmin] = [
                    "id" => $price->id,
                    "min" => $price->product->quantity,
                    "description" => $price->product->description,
                    "price" => $price->price,
                    "currency" => $price->currency->name,
                    "symbol" => $price->currency->symbol,
                    "start_date" => $price->start_date,
                    "end_date" => $price->end_date,
                ];
                ksort($streamPublic);
            }else if($price->product->isStream && !$price->product->isLive && !$price->product->isPublic) {
                $idasmin = substr($price->product->quantity,0,-3);
                $streamPrivate[$idasmin] = [
                    "id" => $price->id,
                    "min" => $price->product->quantity,
                    "description" => $price->product->description,
                    "price" => $price->price,
                    "currency" => $price->currency->name,
                    "symbol" => $price->currency->symbol,
                    "start_date" => $price->start_date,
                    "end_date" => $price->end_date,
                ];
                ksort($streamPrivate);
            }

        }


        // LIST OF MENTORS
        $mentors = getAllMentors();
        
        // CHECK FREE REVIEWS
        $freeMode = $this->checkIfFreeReviews();

        // STREAM REVIEWS
        $start_date_time = date("Y-m-d H:i:s");
        $start_date_time_with_buffer = timezone()->convertFromLocal(new Carbon(date('Y-m-d H:i:s', strtotime('+ '.config("ac.STREAM_TIME_BUFFER").' minutes', strtotime($start_date_time)))))->format('Y-m-d H:i:s');
        $schedules=AcStreams::where(\DB::raw("UNIX_TIMESTAMP(CONCAT(start_date,' ',start_time))"),'>=',strtotime($start_date_time_with_buffer))->where('status',0)->orderBy('start_date','asc')->limit(3)->get();

        return view('frontend.ac.order', compact(['schedules', 'freeMode', 'currencies', 'currency_id', 'mentors', 'reviewer_id', 'price_id', 'livePublic','livePrivate','prerecPublic','prerecPrivate','streamPublic','streamPrivate']));
    }




    public function step1(Request $request)
    {

        session()->forget('stream_id');
        session()->forget('duecalculated');


        //validate the form
        $validatedData = $request->validate([
            //'reviewer' => 'required',
            'priceid' => 'required'
        ]);

        // get price
        $price = AcPrice::find($request->priceid);

        $reviewer=null;
        // get reviewer id
        if($request->has('reviewer') && !empty($request->input('reviewer'))) {
            $reviewer = AcAccount::where('slug', $request->reviewer)->first();
        }else{
            session(['stream_id' => AcStreams::getNextStreamId()]);

            $stream=AcStreams::find(AcStreams::getNextStreamId());

            $reviewer = AcAccount::where('id', $stream->reviewer_id)->first();

            $minutes_to_check=$price->product->quantity;
            if(!$stream->isAvailableSlot(round($minutes_to_check))){
                $request->session()->flash('fail-slot-message', 'Slot is full');
                return redirect()->route('frontend.user.order');

            }
        }



        // REFACTO use updateOrCreate...
        if(session()->exists('order_uuid')) { // UPDATE ORDER

            $order = AcOrder::where('uuid', session('order_uuid'))->firstOrFail();
            $order->amount_df = $price->price;
            $order->amount_total = $price->price;
            $order->currency_id = $price->currency_id;
            $order->save();

            $oi = AcOrderItem::where('order_id', $order->id)->firstOrFail();
            $oi->price_id = $price->id;
            if($reviewer) {
                $oi->reviewer_id = $reviewer->id;
            }

            $oi->product_id = $price->product_id;
            $oi->product_family_id = $price->product->product_family_id;
            $oi->currency_id = $price->currency_id;
            $oi->quantity = $price->product->quantity;
            $oi->unit_price = $price->price;
            $oi->save();

        } else { // NEW ORDER

            // create order
            $order = new AcOrder();
            $order->uuid = Uuid::uuid4()->toString();
            $order->user_id = auth()->user()->id;
            $order->domain_id = 1;
            $order->status_order_id = 1;
            $order->status_delivery_id = 1;
            $order->status_payment_id = 1;
            // FOR NOW NO TAXES
            $order->amount_df = $price->price;
            $order->amount_total = $price->price;
            $order->currency_id = $price->currency_id;
            $order->save();

            // create orderitem
            $oi = new AcOrderItem();
            $oi->order_id = $order->id;
            $oi->domain_id = 1;
            $oi->price_id = $price->id;
            if($reviewer) {
                $oi->reviewer_id = $reviewer->id;
            }

            $oi->product_id = $price->product_id;
            $oi->product_family_id = $price->product->product_family_id;
            $oi->currency_id = $price->currency_id;
            $oi->quantity = $price->product->quantity;
            $oi->unit_price = $price->price;
            $oi->save();

            // we use a session
            session(['order_uuid' => $order->uuid]);

        }

        // redirect to step 2
        return redirect()->route('frontend.user.order.upload');
    }



    //----------------------------------------------------------------
    // STEP 2 - UPLOAD
    //----------------------------------------------------------------
    public function upload()
    {
        // check if order exist if not redirect
        if(!session()->exists('order_uuid')) {
            return redirect()->route('frontend.user.order');
        } else {
            // TODO use order_id (as secure as using uuid with session) to avoid making a query
            $order = AcOrder::where('uuid', session('order_uuid'))->firstOrFail();
            $ac = AcDelivery::where('order_id', $order->id)->first();
            if($ac) {
                // delivery exist
                $videoToReview = $ac->videoToReview;
                $mimeType = $ac->mimeType;
                $size = $ac->size;
                $fps = $ac->fps;
                $note = $ac->note;
                $level = $ac->level;
            } else {
                // new delivery
                $videoToReview = null;
                $mimeType = null;
                $size = null;
                $fps = null;
                $note = null;
                $level = null;
            }
        }

        return view('frontend.ac.order-upload', compact(['videoToReview', 'note', 'level', 'mimeType', 'size', 'fps']));
    }

    public function step2(Request $request)
    {
        //validate the form
        $validatedData = $request->validate([
            'videoToReview' => 'required',
            'note' => 'max:1000',
            'level' => 'required'
        ]);

        // fetch order and order_item
        $order = AcOrder::where('uuid', session('order_uuid'))->firstOrFail();
        $oi = $order->orderitems->first();

        // updateOrCreate delivery
        $user = AcDelivery::updateOrCreate(['order_id' => $order->id], [
            'order_id' => $order->id,
            'order_item_id' => $oi->id,
            'status_id' => 1,
            'unit_id' => $oi->product->unit_id,
            'quantity_sold' => $oi->quantity,
            'user_id' => auth()->user()->id,
            'reviewer_id' => $oi->reviewer_id,
            'videoToReview' => $request->videoToReview,
            'mimeType' => $request->mimeType,
            'size' => $request->size,
            'fps' => $request->fps,
            'note' => $request->note,
            'level' => $request->level,
            'product_id' => $oi->product_id,
            'product_family_id' => $oi->product_family_id
        ]);

        // redirect to step 3
        return redirect()->route('frontend.user.order.payment');
    }



    //----------------------------------------------------------------
    // STEP 3 - PAYMENT
    //----------------------------------------------------------------
    public function payment()
    {
        session()->forget('duecalculated');
        // check if order exist if not redirect
        if(!session()->exists('order_uuid')) {
            return redirect()->route('frontend.user.order');
        } else {

            // FETCH INFO...
            $order = AcOrder::where('uuid', session('order_uuid'))->firstOrFail();
            $oi = $order->orderitems->first();

            // calculate due date
            if(session()->exists('stream_id')){
                $stream=AcStreams::find(session('stream_id'));
                $stream_start_end_time=$stream->getDateTimeDuration(round($oi->product->quantity));
                $oi->stream_start_time=$stream_start_end_time['start'];
                $oi->stream_end_time=$stream_start_end_time['end'];
                $oi->save();
                $oi->stream_id=session('stream_id');
                $duecalculated=$stream->alottedTime(round($oi->product->quantity));
                session(['duecalculated' => $duecalculated]);
            }else{
                $nbtodo = AcDelivery::where('reviewer_id', $oi->reviewer->id)->where('status_id', 2)->sum('quantity_sold');
                $duedatefloat = floatval($nbtodo) / $oi->reviewer->hoursWeek + ($oi->reviewer->delay / 5); // helping the expert, not counting the week-end / 5
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
            }

            $reviewer = $oi->reviewer->user->first_name . " " . $oi->reviewer->user->last_name;
            $type = $oi->product->type;
            $description = $oi->product->description;
			$duedate = $duecalculated;
			$length = $oi->quantity;
			$visibility = $oi->product->isPublic == true ? "Public" : "Private";
            $price = $oi->price->price;
            $currency = $oi->price->currency->name;
            $symbol = $oi->price->currency->symbol;

            $term_id = AcTerm::latest('id')->first();
            if($term_id->id > auth()->user()->term_id) {
                $termApprobation = $term_id->id;
            } else {
                $termApprobation = null;
            }

            // free mode
            $freeMode = $this->checkIfFreeReviews();
            $isFreeProduct = false;
            if($freeMode && !$oi->product->isLive && $oi->product->isPublic && $oi->quantity == 15.00) {
                $isFreeProduct = true;
            }

            return view('frontend.ac.order-payment', compact('freeMode', 'isFreeProduct', 'description', 'type', 'reviewer', 'duedate', 'length', 'visibility', 'price', 'currency', 'symbol', 'termApprobation'));
        }
    }

    public function stripe(Request $request)
    {
        //validate the form
        $validatedData = $request->validate([
            'stripeToken' => 'sometimes|required',
            'terms' => 'sometimes|required'
        ]);

        // user accepted the terms and condition (save the version (id))
        if(!empty($request->terms)) {
            auth()->user()->term_id = $request->terms;
            auth()->user()->save();
        }

        // fetch order and order_item
        $order = AcOrder::where('uuid', session('order_uuid'))->firstOrFail();
        $oi = $order->orderitems->first();

        // CHECK IF FREE PRODUCT (IN FREE MODE ONLY)
        $freeMode = $this->checkIfFreeReviews();
        $isFreeProduct = false;
        if($freeMode && !$oi->product->isLive && $oi->product->isPublic && $oi->quantity == 15.00) {
            $isFreeProduct = true;
        }
        if(!$isFreeProduct) {
            // using stripe-php
            \Stripe\Stripe::setApiKey(config('stripe.stripe_secret'));
            // fetch token provided by stripe.js
            $token = $request->stripeToken;

            //................................................................
            // Canada buyer
            //................................................................
            if($request->has('country') && $request->country == "Canada") {
                //validate the form
                $validatedData = $request->validate([
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'postalcode' => 'required',
                    'country' => 'required',
                ]);
                //save his address
                auth()->user()->address = $request->address;
                auth()->user()->city = $request->city;
                auth()->user()->province = $request->province;
                auth()->user()->postalcode = $request->postalcode;
                auth()->user()->country = $request->country;
                auth()->user()->save();

                //calculate salex taxes based on province
                $taxes = Taxes::calculate($oi->price->price, $request->province);
                //dd($taxes);
                $charge_amount = round($taxes['total'], 2);

            } else { // International buyer
                $charge_amount = $oi->price->price;
            }

            // charge
            try {

                $charge = \Stripe\Charge::create([
                    'amount' => intval(round($charge_amount * 100, 0, PHP_ROUND_HALF_UP)),
                    'currency' => $oi->price->currency->iso,
                    'description' => $oi->product->title,
                    'source' => $token,
                ]);

            } catch(\Stripe\Exception\CardException $e) {
                $request->session()->flash('fail-message', 'Your payment was declined.');
                return redirect()->route('frontend.user.order.payment');
            } catch (\Stripe\Exception\RateLimitException $e) {
                $request->session()->flash('fail-message', 'To many requests to the API.');
                return redirect()->route('frontend.user.order.payment');
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $request->session()->flash('fail-message', 'Sorry we are unable to process your payment.');
                return redirect()->route('frontend.user.order.payment');
            } catch (\Stripe\Exception\AuthenticationException $e) {
                $request->session()->flash('fail-message', 'There are problems with authentication.');
                return redirect()->route('frontend.user.order.payment');
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                $request->session()->flash('fail-message', 'There is a problem with the network.');
                return redirect()->route('frontend.user.order.payment');
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $request->session()->flash('fail-message', 'There is a problem with the API.');
                return redirect()->route('frontend.user.order.payment');
            } catch (Exception $e) {
                $request->session()->flash('fail-message', 'Sorry we are unable to process your payment.');
                return redirect()->route('frontend.user.order.payment');
            }
        } else {
            $charge_amount = 0.00;
        }

        // save payment
        if((isset($charge->paid) && $charge->paid) || $isFreeProduct) {
            // save payment in ac_deliveries
            $acd = new AcPayment();
            $acd->payment_type_id = $isFreeProduct ? 2 : 1; // stripe 1, free reviews 2
            $acd->order_id = $order->id;
            $acd->status_id = 4; // DONE
            $acd->user_id = auth()->user()->id;
            $acd->description = $oi->product->title;
            $acd->amount = $isFreeProduct ? 0.00 : $oi->price->price;
            $acd->currency_id = $oi->price->currency->id;
            $acd->currency = $oi->price->currency->iso;
            $acd->charge_id = $isFreeProduct ? null : $charge->id;
            $acd->charge_timestamp = $isFreeProduct ? time() : $charge->created;
            $acd->receipt_url = $isFreeProduct ? null : $charge->receipt_url;
            $acd->save();

            // update order.status
            $order->status_delivery_id = 2; // TODO
            $order->status_payment_id = 4; // DONE
            // taxes if canada
            if($request->has('country') && $request->country == "Canada") {
                $order->amount_pst = isset($taxes['taxes_details']['pst']) ? round($taxes['taxes_details']['pst'],2) : null;
                $order->amount_qst = isset($taxes['taxes_details']['qst']) ? round($taxes['taxes_details']['qst'],2) : null;
                $order->amount_gst = isset($taxes['taxes_details']['gst']) ? round($taxes['taxes_details']['gst'],2) : null;
                $order->amount_hst = isset($taxes['taxes_details']['hst']) ? round($taxes['taxes_details']['hst'],2) : null;
                $order->amount_df = round($taxes['sub_total'],2);
                $order->amount_total = $charge_amount;

                $order->name = $request->name;
                $order->address = $request->address;
                $order->city = $request->city;
                $order->province = $request->province;
                $order->postalcode = $request->postalcode;
                $order->country = $request->country;
            }
            $order->currency = $oi->price->currency->iso;
            $order->amount_paid = $charge_amount;
            $order->save();


            // update delivery.status
            if (!session()->exists('stream_id')) {
                $nbtodo = AcDelivery::where('reviewer_id', $oi->reviewer->id)->where('status_id', 2)->sum('quantity_sold');
                $duedatefloat = floatval($nbtodo) / $oi->reviewer->hoursWeek + ($oi->reviewer->delay / 5); // helping the expert, not counting the week-end / 5
                $addweek = intval($duedatefloat) + 1;
                $order->delivery->due_date = Carbon::now()->add(1, 'day')->add($addweek, 'week')->format('Y-m-d');
            }
            
            $order->delivery->status_id = 2; // TODO
            $order->delivery->save();

            session(['orderdate' => date('Y-m-d H:i:s')]);
            $ordernb = substr(session('order_uuid'), 0, 6);
            

            //................................................................
            // TODO EVENTUALLY.... REFACTOR (use queue)
            //................................................................
            if(config("ac.EXTERNAL_API_ORDER_CREATION")) {
                
                //................................................................
                // CREATE NEXTCLOUD FOLDER
                //................................................................
                try {
                    $client = new \GuzzleHttp\Client();
                    $folderToCreate =  $oi->reviewer->nextcloudUsername . "/" . substr($order->user->first_name, 0, 1) . $order->user->last_name . "_" . $ordernb;
                    $url = 'https://'.config("ac.NC_DNS").'/remote.php/dav/files/'.config("ac.NC_WEBDAV_USERNAME").'/'.config("ac.NC_WEBDAV_REVIEWERFOLDER") . $folderToCreate;
                    $response = $client->request('MKCOL', $url, ['auth' => [config("ac.NC_WEBDAV_USERNAME"), config("ac.NC_WEBDAV_PWD")]]);
                } catch (\Exception $e) {
                    $html = "<p>Nextcloud error</p>";
                    Mail::send(array(), array(), function ($message) use ($html) {
                        $message->to("patrick@agora.studio")
                        ->subject("AC - Nextcloud error")
                        ->from("patrick@agora.studio")
                        ->setBody($html, 'text/html');
                    });
                }


                //................................................................
                // CREATE SYNCSKETCH REVIEW
                //................................................................
                try {
                    $client = new \GuzzleHttp\Client();

                    $taskTitle = substr($oi->reviewer->user->first_name, 0, 1) . $oi->reviewer->user->last_name;
                    $taskTitle .= " by " . $order->user->full_name;
                    $taskTitle .= " (" . $oi->product->title . " - " . $oi->product->quantity . ")";

                    $url = 'https://www.syncsketch.com/api/v1/review/?username='.config("ac.SYNCSKETCH_API_USERNAME").'&api_key='.config("ac.SYNCSKETCH_API_KEY");
                    $response = $client->post($url,
                        ['body' => json_encode(
                            [
                                'name' => $taskTitle,
                                'description' => $order->delivery->note,
                                'project' => '/api/v1/project/' . config("ac.SYNCSKETCH_AC_PROJECT") . '/',
                            ]
                        )]
                    );
                    $syncSketchReviewID = json_decode($response->getBody())->id;
                    $syncSketchReviewURL = json_decode($response->getBody())->reviewURL;

                    $url = 'https://www.syncsketch.com/api/v1/item/?username='.config("ac.SYNCSKETCH_API_USERNAME").'&api_key='.config("ac.SYNCSKETCH_API_KEY");
                    $response = $client->post($url,
                        ['body' => json_encode(
                            [
                                'reviewId' => $syncSketchReviewID,
                                'external_url' => asset_cdn('uploadReviews/' . $order->delivery->videoToReview . '?id=1'),
                                'fps' => $order->delivery->fps,
                                'status' => 'done',
                                'name' => $taskTitle
                            ]
                        )]
                    );

                } catch (\Exception $e) {

                    $html = "<p>Syncsketch error</p>";
                    Mail::send(array(), array(), function ($message) use ($html) {
                        $message->to("patrick@agora.studio")
                        ->subject("AC - Syncsketch error")
                        ->from("patrick@agora.studio")
                        ->setBody($html, 'text/html');
                    });

                }


                //................................................................
                // CREATE TASK IN ASANA - very html tags limited...
                //................................................................
                $taskTitle = "Order for " . substr($oi->reviewer->user->first_name, 0, 1) . $oi->reviewer->user->last_name;
                $taskTitle .= " by " . $order->user->full_name;
                $taskTitle .= " | " . $ordernb;

                $item = "Ordered at " . session('orderdate') . "(UTC)";
                $item .= " | ID: " . session('order_uuid') . " | ";
                $item .= $oi->product->type;
                $item .= " review | ";
                $item .= $oi->product->isPublic == true ? "Public" : "Private";
                $item .= " | <em>~" . substr($oi->quantity, 0, -3) . " minutes</em>";
                $item .= " | Reviewer: <strong>" . $oi->reviewer->user->first_name . " " . $oi->reviewer->user->last_name . "</strong>";
                $item .= " | Request by: <strong>".$order->user->full_name."</strong>" . " " . $order->user->email;
                $item .= " | Skill level: " . $order->delivery->level;
                $item .= ' | ' . htmlspecialchars(str_replace('"', '', $order->delivery->note));
                $item .= " | Syncsketch URL: " . $syncSketchReviewURL;
                $item .= ' | ' . asset_cdn('/uploadReviews/' . $order->delivery->videoToReview);

                try {
                    // prep the object
                    $isPublic = $oi->product->isPublic == true ? "Public" : "Private";
                    $isLive = $oi->product->type;
                    $asanaObject = array(
                        'projects' => '1194707198731944',
                        'name' => $taskTitle,
                        'due_on' => $order->delivery->due_date->subDays(3)->format('Y-m-d'),
                        'assignee' => $oi->reviewer->asanaGID,
                        'followers' => array("1193323448180498"),
                        'custom_fields' => array(
                            "1199115343918347" => substr($oi->quantity, 0, -3),
                            "1199115198309009" => $isLive, //isLive
                            "1199115191502567" => $isPublic, //isPublic
                        ),
                        'html_notes' => '<body>' . $item . '</body>'
                    );

                    // create task
                    $client = Client::accessToken(config('ac.ASANA_PERSONAL_ACCESS_TOKEN'));
                    $result = $client->tasks->createTask($asanaObject, array('opt_pretty' => 'true'));

                } catch (\Exception $e) {

                    $html = "<p>Asana error: ".serialize($asanaObject)."</p>";
                    Mail::send(array(), array(), function ($message) use ($html) {
                        $message->to("patrick@agora.studio")
                        ->subject("AC - Asana error")
                        ->from("patrick@agora.studio")
                        ->setBody($html, 'text/html');
                    });

                }
            }



            // ZOOM MEETING - REFACTO
            $meeting=null;
            session()->forget('meeting');
            if(session()->exists('stream_id')) {
                $meeting=ZoomHelper::create_meeting( $oi->stream_start_time,round($oi->quantity),'');
                session(['meeting' => $meeting]);
            }


            //................................................................
            // SEND EMAIL -- REFACTO IN JOB/QUEUE
            //................................................................
            // EMAIL TO USER
            $item = $oi->product->type;
            $item .= " review<br>";
            if($oi->reviewer) {
                $item .= "Reviewer: " . $oi->reviewer->user->first_name . " " . $oi->reviewer->user->last_name . "<br>";
            }
            $item .= $oi->product->description . " <i>~" . substr($oi->quantity, 0, -3) . " minutes</i><br>";
            $item .= "Visibility: ";
            $item .= $oi->product->isPublic == true ? "Public" : "Private";
            
            Mail::to(config('app.env') == "production" ? auth()->user()->email : "patrick@agora.studio")->send(
                new NewOrderReviewToUser(
                    auth()->user()->first_name,
                    session('order_uuid'),
                    session('orderdate'),
                    $item,
                    $charge_amount,
                    $oi->price->currency->name,
                    $oi->price->currency->symbol,
                    $meeting
                )
            );

            // EMAIL TO REVIEWER
            $itemreviewer = $oi->product->type;
            $itemreviewer .= " review<br>";
            $itemreviewer .= "User: " . auth()->user()->first_name . " " . auth()->user()->last_name . "<br>";
            $itemreviewer .= $oi->product->description . " <i>~" . substr($oi->quantity, 0, -3) . " minutes</i><br>";
			$itemreviewer .= "Visibility: ";
            $itemreviewer .= $oi->product->isPublic == true ? "Public" : "Private";
            $duedate = $order->delivery->due_date ? $order->delivery->due_date->diffForHumans() : '';
            if(isset($syncSketchReviewURL)) {
                $video = $syncSketchReviewURL;
            } else {
                $video = asset_cdn('/uploadReviews/' . $order->delivery->videoToReview);
            }
            $note = $order->delivery->note;

            if(session()->exists('stream_id')) {
                $stream_id = session('stream_id');
                $stream = AcStreams::find($stream_id);
                $duedate=timezone()->convertToLocalFromTimeZone($stream->reviewer->user->timezone,new Carbon($oi->stream_start_time)).' until '.timezone()->convertToLocalFromTimeZone($stream->reviewer->user->timezone,new Carbon($oi->stream_end_time),'h:i');

            }

            Mail::to(config('app.env') == "production" ? $oi->reviewer->user->email : "patrick@agora.studio")->send(
                    new NewOrderReviewToMentor(
                        $oi->reviewer->user->first_name,
                        session('order_uuid'),
                        session('orderdate'),
                        $itemreviewer,
                        $duedate,
                        $video,
                        $note,
                        $meeting
                    )
            );


            // EMAIL TO AGORA
            $item = $oi->product->type;
            $item .= " review<br>";
            if($oi->reviewer) {
                $item .= "Reviewer: " . $oi->reviewer->user->first_name . " " . $oi->reviewer->user->last_name . "<br>";
            }
            $item .= "User: " . auth()->user()->first_name . " " . auth()->user()->last_name . "<br>";
            $item .= $oi->product->description . " <em>~" . substr($oi->quantity, 0, -3) . " minutes</em><br>";
            $item .= "Visibility: ";
            $item .= $oi->product->isPublic == true ? "Public" : "Private";
            $duedate = $order->delivery->due_date ? $order->delivery->due_date->diffForHumans() : '';
            $video = $order->delivery->videoToReview;
            $note = $order->delivery->note;

            Mail::to(config('app.env') == "production" ? "info@agora.studio" : "patrick@agora.studio")->send(
                new NewOrderReviewToAgora(
                    auth()->user()->first_name,
                    session('order_uuid'),
                    session('orderdate'),
                    $item,
                    $charge_amount,
                    $duedate,
                    $video,
                    $note,
                    $oi->price->currency->name,
                    $oi->price->currency->symbol
                )
            );

            if(session()->exists('stream_id')){
                $oi->stream_id=session('stream_id');
                //$oi->stream_time=session('duecalculated');
                $stream=AcStreams::find($oi->stream_id);
                $oi->zoom_data=json_encode($meeting);
                $oi->save();
                if($stream->isSlotsFull()){
                    $stream->status=1;
                    $stream->save();
                }
            }
            //redirect
            return redirect()->route('frontend.user.order.confirmation');

        } else {
            $request->session()->flash('fail-message', 'Sorry we are unable to process your payment.');
            return redirect()->route('frontend.user.order.payment');
        }
    }

    //----------------------------------------------------------------
    // STEP 4 - CONFIRMATION
    //----------------------------------------------------------------

    public function confirmation()
    {


        // check if order exist if not redirect
        if(!session()->exists('order_uuid')) {
            return redirect()->route('frontend.user.order');
        } else {
            $uuid = session('order_uuid');
            $useremail = auth()->user()->email;
            // fetch order and order_item
            $order = AcOrder::where('uuid', session('order_uuid'))->firstOrFail();
            $oi = $order->orderitems->first();
            // order is completed delete session

            $item = $oi->product->type;
            $item .= " review<br>";
            if($oi->reviewer) {
                $item .= "Reviewer: " . $oi->reviewer->user->first_name . " " . $oi->reviewer->user->last_name . "<br>";
            }
            $item .= $oi->product->description . " <i>~" . substr($oi->quantity, 0, -3) . " minutes</i><br>";
            $item .= "Visibility: ";
            $item .= $oi->product->isPublic == true ? "Public" : "Private";
            $price = $order->amount_paid;
            $symbol = $oi->price->currency->symbol;
            $currency = $oi->price->currency->name;
            $orderdate = session('orderdate');

            $meeting=null;
            if(session()->exists('meeting')){
                $meeting=session('meeting');
            }

            session()->forget('order_uuid');
            session()->forget('orderdate');
            session()->forget('meeting');


            return view('frontend.ac.order-confirmation', compact(['meeting','uuid', 'useremail', 'price', 'orderdate', 'item', 'symbol', 'currency']));
        }
    }


    private function checkIfFreeReviews()
    {
        // FREE REVIEWS IS ON?
        $freeMode = false;
        $freeReviews = config('ac.FREE_REVIEWS');
        if($freeReviews) {
            // calculate number of free reviews given so far
            $nbFreeReviewsSoFar = AcPayment::where('payment_type_id', 2)->count();
            if($freeReviews > $nbFreeReviewsSoFar) {
                $freeMode = true;
            }
        }
        return $freeMode;
    }

}
