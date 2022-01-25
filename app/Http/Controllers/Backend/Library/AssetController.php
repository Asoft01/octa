<?php

namespace App\Http\Controllers\Backend\Library;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\Library\Traits\FindOrCreateAcTags;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

use App\DataTables\AssetsDataTable;
use App\Models\AcDomain;
use App\Models\AcAsset;
use App\Models\AcContent;
use App\Models\AcContentTag;
use App\Models\AcContentUserMetric;
use App\Models\AcHyvor;
use App\Models\AcTag;


class AssetController extends Controller
{

	use FindOrCreateAcTags;

    public function index(AssetsDataTable $dataTable)
    {
        return $dataTable->render('backend.library.index');
    }


    public function create()
    {
        $nextId = $uuid = Str::uuid()->toString();

        return view('backend.library.assets.createOrEdit', compact('nextId'))
            ->withDomain(AcDomain::get()->pluck('title','id')->toArray())
			->withTag(AcTag::OrderBy('title')->get()->pluck('title','id')->toArray());
    }

    public function edit($id)
    {
        $asset = AcAsset::findOrFail($id);
        $content = $asset->content;
        if(!isset($asset->uuid)) {
            $oldpath = explode('/',$asset->video);
            if(count($oldpath) == 3) {
                $nextId = $oldpath[1];
            } else if(count($oldpath) == 4) {
                $nextId = $oldpath[1] . '/' . $oldpath[2];
            } else if(count($oldpath) == 5) {
                $nextId = $oldpath[1] . '/' . $oldpath[2] . '/' . $oldpath[3];
            }
        } else {
            $nextId = $asset->uuid;
        }

        return view('backend.library.assets.createOrEdit', compact('nextId', 'asset', 'content'))
            ->withDomain(AcDomain::get()->pluck('title','id')->toArray())
			->withTag(AcTag::OrderBy('title')->get()->pluck('title','id')->toArray());
    }

    // either create or update
    public function store(Request $request)
    {
        //validate the form
        $validatedData = $request->validate([
            'domain_id' => 'required',
            'description' => 'required',
            'posterFile' => 'required',
            'videoFullFile' => 'required',
            'videoPreviewFile' => 'required',
            'zipFile' => 'required',
        ]);
        if($request->assetID) { //edit
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

        $asset = AcAsset::updateOrCreate(['id' => $request->assetID], [
            'uuid' => $request->pathID,
            'zip' => 'assets/'.$request->pathID.'/'.$request->zipFile,
            'filesize' => $request->zipSize,
            'thumb' => 'assets/'.$request->pathID.'/'.$request->thumbFile,
            'poster' => 'assets/'.$request->pathID.'/'.$request->posterFile,
            'poster_cotd' => $request->posterCOTDFile == null ? null : 'assets/'.$request->pathID.'/'.$request->posterCOTDFile,
            'preview_video' => 'assets/'.$request->pathID.'/'.$request->videoPreviewFile,
            'video' => 'assets/'.$request->pathID.'/'.$request->videoFullFile,
            'poster_intro' => $request->posterIntroFile == null ? null : 'assets/'.$request->pathID.'/'.$request->posterIntroFile,
            'intro_video' => $request->videoIntroFile == null ? null : 'assets/'.$request->pathID.'/'.$request->videoIntroFile,
            'releaseDate' => $request->releaseDate,
        ]);

        $content = AcContent::updateOrCreate(['id' => $request->contentID], [
            'contentable_type' => 'MorphAsset',
            'contentable_id' => $asset->id,
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

		// sync tags
		$tags = $this->FindOrCreateAcTags($request->tags, $request->domain_id);
		$content->tags()->sync($tags->pluck('id')->all());

        // add medias (for gallery)
        if($request->galleryFile) {
            $asset->clearMediaCollection('images');
            foreach($request->galleryFile as $img) {
                $asset->addMediaFromString($img)->usingFileName($img)->withCustomProperties(['path' => 'assets/'.$request->pathID.'/'])->toMediaCollection('images');
            }
        }

        return redirect()->route('admin.library.assets')->withFlashSuccess("Success");
    }

    public function delete($id) {
        $asset = AcAsset::findOrFail($id);
        
        // delete s3 directory
        if(!isset($asset->uuid)) {
            dd("Ask Pat");
        }
        Storage::disk('s3')->deleteDirectory("assets/".(string)$asset->uuid);
        
        // delete database entry

        // user metrics
        AcContentUserMetric::where('content_id', $asset->content->id)->delete();

        // comments
        AcHyvor::where('content_id', $asset->content->id)->delete();

		// tag
        AcContentTag::where('ac_content_id', $asset->content->id)->delete();

        AcContent::find($asset->content->id)->delete();
        $asset->delete();

        return redirect()->route('admin.library.assets')->withFlashSuccess("Success");
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
