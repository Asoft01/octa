<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcReview extends Model
{
    protected $dates = ['releaseDate', 'created_at', 'updated_at' ];
    // todo maybe adding artist? but adds 4 queries?!?
    protected $with = ['mentor'];
    protected $guarded = [];

    public function content()
    {
        //return $this->morphOne('App\Models\AcContent', 'contentable')->where('display_start', '<=', now())->orderBy('display_start', 'DESC');
        return $this->morphOne('App\Models\AcContent', 'contentable');
    }

    public function mentor()
    {
        return $this->belongsTo('App\Models\AcAccount', 'mentor_id');
    }

    public function artist()
    {
        return $this->belongsTo('App\Models\AcAccount', 'artist_id');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Models\AcDelivery');
    }

    public function getMorphClass()
    {
        return 'MorphReview';
    }

    
}
