<?php

namespace App\Http\Controllers\Backend\Library;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\Library\Traits\FindOrCreateAcTags;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

use App\DataTables\ReviewsDataTable;
use App\Models\AcDomain;
use App\Models\AcReview;
use App\Models\AcContent;
use App\Models\AcCategory;
use App\Models\AcCategoryContent;
use App\Models\AcTag;
use App\Models\AcDelivery;
use App\Models\AcOrder;
use App\Models\AcContentTag;
use App\Models\AcAccount;
use App\Models\AcContentUserMetric;
use App\Models\AcHyvor;

use App\Models\Auth\User;
use App\Models\Auth\Role;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyReviewIsPublished;


/**
 * Class UserController.
 */
class ReviewController extends Controller
{

	use FindOrCreateAcTags;

    public function index(ReviewsDataTable $dataTable)
    {
        return $dataTable->render('backend.library.index');
    }

    public function create()
    {
        $nextId = $uuid = Str::uuid()->toString();
        $reviewers = getAllMentors()->users->sortBy('full_name')->pluck('full_name','account.id')->prepend('', '')->toArray();
        
        return view('backend.library.reviews.createOrEdit', compact('nextId'))
            ->withDomain(AcDomain::get()->pluck('title','id')->toArray())
            ->withOrder(AcOrder::where('status_order_id', '!=', 4)->get()->pluck('uuid','uuid')->toArray())
            ->withReviewers($reviewers)
            ->withTag(AcTag::OrderBy('title')->get()->pluck('title','id')->toArray());
    }

    public function edit($id)
    {
        $review = AcReview::findOrFail($id);
        $content = $review->content;
        $reviewers = getAllMentors()->users->sortBy('full_name')->pluck('full_name','account.id')->prepend('', '')->toArray();
        if(!isset($review->uuid)) {
            $oldpath = explode('/',$review->video);
            if(count($oldpath) == 3) {
                $nextId = $oldpath[1];
            } else if(count($oldpath) == 4) {
                $nextId = $oldpath[1] . '/' . $oldpath[2];
            } else if(count($oldpath) == 5) {
                $nextId = $oldpath[1] . '/' . $oldpath[2] . '/' . $oldpath[3];
            }
        } else {
            $nextId = $review->uuid;
        }

        return view('backend.library.reviews.createOrEdit', compact('nextId', 'review', 'content'))
            ->withDomain(AcDomain::get()->pluck('title','id')->toArray())
            ->withReviewers($reviewers)
            ->withTag(AcTag::OrderBy('title')->get()->pluck('title','id')->toArray());
    }

    // either create or update
    public function store(Request $request)
    {
        //validate the form -- //todo validate either embed (+https...) or videoFull - only in front for now
        $validatedData = $request->validate([
			//'mentor_id' => 'required|exists:ac_accounts,id',
            'domain_id' => 'required',
            'description' => 'required',
            'posterFile' => 'required',
            'videoFullFile' => 'required',
            'videoPreviewFile' => 'required',
        ]);
        if($request->reviewID) { //edit
            $validatedData = $request->validate(['title' => ['required']]);
        } else { // create
            if($request->slug == null) {
                $validatedData = $request->validate(['title' => ['required', 'unique:ac_contents,title']]);
            } else {
                $validatedData = $request->validate(['title' => ['required']]);
            }
        }

        // prep data
        $slug = $request->slug == null ? Str::slug($request->title, '-') : $request->slug;
        $display_start = $request->display_start == null ? now() : $request->display_start;
        
        // coming from an order?
        if(isset($request->delivery_id) && !empty($request->delivery_id)) {
            // todo add check if status_payment_id == 4
            $order = AcOrder::where('uuid', $request->delivery_id)->firstOrFail();
            $oi = $order->orderitems->first();

            // fetch data from order replacing request
            $request->mentor_id = $oi->reviewer_id;
            $isLive = $oi->product->isLive;
            $isPublic = $oi->product->isPublic;
        } else {
            $isLive = isset($request->isLive) ? true : false;
            $isPublic = true;
        }

        $review = AcReview::updateOrCreate(['id' => $request->reviewID], [
            'mentor_id' => $request->mentor_id,
            'artist_id' => $request->delivery_id == null ? null : optional($order->user)->id,
            'uuid' => $request->pathID,
            'thumb' => $request->thumbFile == null ? null : 'reviews/'.$request->pathID.'/'.$request->thumbFile,
            'poster' => 'reviews/'.$request->pathID.'/'.$request->posterFile,
            'poster_cotd' => $request->posterCOTDFile == null ? null : 'reviews/'.$request->pathID.'/'.$request->posterCOTDFile,
            'preview_video' => 'reviews/'.$request->pathID.'/'.$request->videoPreviewFile,
            'video' => 'reviews/'.$request->pathID.'/'.$request->videoFullFile,
            'fps' => !empty($request->videoFullFile) ? $request->videoFullFPS : 30,
            'length' => $request->length == null ? null : $request->length,
            'syncsketch' => $request->syncsketch == null ? null : $request->syncsketch,
            'isLive' => $isLive,
            'releaseDate' => $request->releaseDate,
        ]);

        $content = AcContent::updateOrCreate(['id' => $request->contentID], [
            'contentable_type' => 'MorphReview',
            'contentable_id' => $review->id,
            'domain_id' => $request->domain_id,
            'title' => $request->title,
            'title_cotd' => $request->title_cotd,
            'description' => $request->description,
            'description_cotd' => $request->description_cotd,
            'slug' => $slug,
            'isPublic' => $isPublic,
            'delivery_id' => $request->delivery_id == null ? null : $order->delivery->id,
            'display_start' => $display_start,
            'display_end' => $request->display_end,
            'cotd_start' => $request->cotd_start
        ]);


        // categories
        if(!$request->reviewID) {
            AcCategoryContent::create([
                'ac_content_id' => $content->id,
                'ac_category_id' => 10
            ]);
        }

		// sync tags
		$tags = $this->FindOrCreateAcTags($request->tags, $request->domain_id);
		$content->tags()->sync($tags->pluck('id')->all());

        // close order
        if((isset($request->delivery_id) && !empty($request->delivery_id)) && $order->status_order_id != 4) {
            // email user review is ready
            Mail::to(config('app.env') == "production" ? $order->user->email : "patrick@agora.studio")->send(
                new NotifyReviewIsPublished(
                    $order->user->first_name,
                    $request->delivery_id)
            );
            
            // close order
            $order->status_order_id = 4;
            $order->status_delivery_id = 4;
            $order->delivery->status_id = 4;
            $order->delivery->completed_date = now();
            $order->delivery->quantity_delivered = $oi->quantity;
            $order->delivery->save();
            $order->save();
        }

        return redirect()->route('admin.library.reviews')->withFlashSuccess("Success");
    }

    public function delete($id) {
    
        $review = AcReview::findOrFail($id);
        
        // delete s3 directory
        if(!isset($review->uuid)) {
            dd("Ask Pat");
        }
        Storage::disk('s3')->deleteDirectory("reviews/".(string)$review->uuid);
        
        // delete database entry

        // user metrics
        AcContentUserMetric::where('content_id', $review->content->id)->delete();

        // comments
        AcHyvor::where('content_id', $video->content->id)->delete();

        // user metrics
        AcContentUserMetric::where('content_id', $review->content->id)->delete();

        // category
        AcCategoryContent::where('ac_content_id', $review->content->id)->delete();
        
        // tag
        AcContentTag::where('ac_content_id', $review->content->id)->delete();

        AcContent::find($review->content->id)->delete();
        $review->delete();

        return redirect()->route('admin.library.reviews')->withFlashSuccess("Success");
    }

	public function slug(Request $request)
	{
		$content = (string) $request->post('content');
		$generate = (bool) $request->post('generate');
		$slug = $generate ? Str::slug($content) : $content;
		$exists = AcContent::where('slug', $slug)->exists();
		return response()->json([
			'slug' => $slug,
			'exists' => $exists
		]);
	}
}
