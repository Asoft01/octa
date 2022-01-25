<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AcAsset extends Model implements HasMedia
{
    protected $guarded = [];
    use InteractsWithMedia;
    protected $dates = ['releaseDate', 'created_at', 'updated_at' ];

    public function content()
    {
        return $this->morphOne('App\Models\AcContent', 'contentable');
    }

    // FAKE so that $ct = App\Models\AcTag::with('contents.categories', 'contents.contentable.mentor')->where('title', $tag->title)->take(1)->get();
    //do not complain
    public function mentor()
    {
        return $this->belongsTo('App\Models\AcAccount', 'mentor_id');
    }

    public function getMorphClass()
    {
        return 'MorphAsset';
    }

}
