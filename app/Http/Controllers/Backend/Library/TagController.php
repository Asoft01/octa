<?php

namespace App\Http\Controllers\Backend\Library;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

use App\DataTables\TagsDataTable;
use App\Models\AcTag;
use App\Models\AcContentTag;



class TagController extends Controller
{

    public function index(TagsDataTable $dataTable)
    {
        return $dataTable->render('backend.library.index');
    }


    public function edit($id)
    {
        $tag = AcTag::findOrFail($id);

        return view('backend.library.tags.createOrEdit', compact('tag'));
    }

    // either create or update
    public function store(Request $request)
    {
        //validate the form
        $validatedData = $request->validate([
            'title' => 'required',
        ]);


        $asset = AcTag::updateOrCreate(['id' => $request->tagID], [
            'title' => $request->title
        ]);

        return redirect()->route('admin.library.tags')->withFlashSuccess("Success");
    }

    public function delete($id) {

        $tag = AcTag::findOrFail($id);
        
        // delete s3 directory
        $act = AcContentTag::where('ac_tag_id', $tag->id);
        $act->delete();
        // delete database entrt

        $tag->delete();

        return redirect()->route('admin.library.tags')->withFlashSuccess("Success");
    }

}

