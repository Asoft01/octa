<?php

namespace App\Http\Controllers\Backend\Library;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\Library\Traits\FindOrCreateAcTags;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use DB;

use App\DataTables\VideosDataTable;
use App\DataTables\VideosNoThumbDataTable;
use App\Models\AcDomain;
use App\Models\AcVideo;
use App\Models\AcContent;
use App\Models\AcCategory;
use App\Models\AcCategoryContent;
use App\Models\AcTag;
use App\Models\AcContentTag;
use App\Models\AcAccount;
use App\Models\AcContentUserMetric;
use App\Models\AcHyvor;
use App\Models\AcWatchlistItem;
use App\Models\AcFavorite;
use App\Models\AcContentUserVote;

use App\Models\Auth\User;
use App\Models\Auth\Role;


class VideoController extends Controller
{
	use FindOrCreateAcTags;

    public function index(VideosDataTable $dataTable)
    {       
        return $dataTable->render('backend.library.index');
    }

    public function nothumb(VideosNoThumbDataTable $dataTable)
    {       
        return $dataTable->render('backend.library.index');
    }

    public function create()
    {
        $nextId = $uuid = Str::uuid()->toString();
        $contributors = getAllContributors()->users->sortBy('full_name')->pluck('full_name','account.id')->prepend('Select a contribut', '')->toArray();
        
        return view('backend.library.videos.createOrEdit', compact('nextId'))
            ->withDomain(AcDomain::get()->pluck('title','id')->toArray())
            ->withContributor($contributors)
            ->withTag(AcTag::OrderBy('title')->get()->pluck('title','id')->toArray())
            ->withCategory(AcCategory::OrderBy('title')->get()->pluck('title','id')->toArray());
    }

    public function edit($id)
    {
        $video = AcVideo::findOrFail($id);
        $content = $video->content;
        $contributors = getAllContributors()->users->sortBy('full_name')->pluck('full_name','account.id')->prepend('Select a contribut', '')->toArray();
        if(!isset($video->uuid)) {
            
            $oldpath = explode('/',$video->video);
            
            if(count($oldpath) == 3) {
                $nextId = $oldpath[1];
            } else if(count($oldpath) == 4) {
                $nextId = $oldpath[1] . '/' . $oldpath[2];
            } else if(count($oldpath) == 5) {
                $nextId = $oldpath[1] . '/' . $oldpath[2] . '/' . $oldpath[3];
            }
            
        } else {
            $nextId = $video->uuid;
        }

        return view('backend.library.videos.createOrEdit', compact('nextId', 'video', 'content'))
            ->withDomain(AcDomain::get()->pluck('title','id')->toArray())
            ->withContributor($contributors)
            ->withTag(AcTag::OrderBy('title')->get()->pluck('title','id')->toArray())
            ->withCategory(AcCategory::OrderBy('title')->get()->pluck('title','id')->toArray());
    }

    // either create or update
    public function store(Request $request)
    {
        //validate the form -- //todo validate either embed (+https...) or videoFull - only in front for now
        $validatedData = $request->validate([
			'mentor_id' => 'required|exists:ac_accounts,id',
            'domain_id' => 'required',
            'categories' => 'required',
            'description' => 'required',
            'posterFile' => 'required',
        ]);
        if($request->videoID) { //edit
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
        $videoFileOrURL = !empty($request->embed) ? $request->embed : 'videos/'.$request->pathID.'/'.$request->videoFullFile;
        
        $video = AcVideo::updateOrCreate(['id' => $request->videoID], [
            'mentor_id' => $request->mentor_id,
            'uuid' => $request->pathID,
            'thumb' => $request->thumbFile == null ? null : 'videos/'.$request->pathID.'/'.$request->thumbFile,
            'poster' => 'videos/'.$request->pathID.'/'.$request->posterFile,
            'poster_cotd' => $request->posterCOTDFile == null ? null : 'videos/'.$request->pathID.'/'.$request->posterCOTDFile,
            'preview_video' => $request->videoPreviewFile == null ? null : 'videos/'.$request->pathID.'/'.$request->videoPreviewFile,
            'video' => $videoFileOrURL,
            'fps' => !empty($request->videoFullFile) ? $request->videoFullFPS : 30,
            'length' => $request->length == null ? null : $request->length,
            'releaseDate' => $request->releaseDate,
        ]);

        $content = AcContent::updateOrCreate(['id' => $request->contentID], [
            'contentable_type' => 'MorphVideo',
            'contentable_id' => $video->id,
            'domain_id' => $request->domain_id,
            'title' => $request->title,
            'title_cotd' => $request->title_cotd,
            'description' => $request->description,
            'description_cotd' => $request->description_cotd,
            'slug' => $slug,
            'isPublic' => 1,
            'display_start' => $display_start,
            'display_end' => $request->display_end,
            'cotd_start' => $request->cotd_start
        ]);


        // categories
        if($request->videoID) { //edit
            AcCategoryContent::where('ac_content_id', $request->contentID)->delete();
        }
        foreach(Arr::wrap($request->categories) as $category) {
            AcCategoryContent::create([
                'ac_content_id' => $content->id,
                'ac_category_id' => $category
            ]);
        }

		// sync tags
		$tags = $this->FindOrCreateAcTags($request->tags, $request->domain_id);
		$content->tags()->sync($tags->pluck('id')->all());

        return redirect()->route('admin.library.videos')->withFlashSuccess("Success");
    }

    public function delete($id) {
    
        $video = AcVideo::findOrFail($id);

        
        
        // delete s3 directory
        if(!isset($video->uuid)) {
            dd("Ask Pat");
        }
        Storage::disk('s3')->deleteDirectory("videos/".(string)$video->uuid);
    
        // delete database entry

        // user metrics
        AcContentUserMetric::where('content_id', $video->content->id)->delete();

        // watchlist
        AcWatchlistItem::where('content_id', $video->content->id)->delete();

        // favorite
        AcFavorite::where('content_id', $video->content->id)->delete();

        // comments
        AcHyvor::where('content_id', $video->content->id)->delete();

        // user vote
        AcContentUserVote::where('content_id', $video->content->id)->delete();

        // category
        AcCategoryContent::where('ac_content_id', $video->content->id)->delete();
        
        // tag
        AcContentTag::where('ac_content_id', $video->content->id)->delete();

        AcContent::find($video->content->id)->delete();
        $video->delete();

        return redirect()->route('admin.library.videos')->withFlashSuccess("Success");
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
