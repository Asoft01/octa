<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcVideo extends Model
{
    protected $dates = ['releaseDate', 'created_at', 'updated_at' ];
    protected $with = ['mentor'];
    protected $guarded = [];

    public function content()
    {
        return $this->morphOne('App\Models\AcContent', 'contentable');
    }

    public function mentor()
    {
        return $this->belongsTo('App\Models\AcAccount', 'mentor_id');
    }

    public function getMorphClass()
    {
        return 'MorphVideo';
    }

    public function getSecondsAttribute() {
        sscanf($this->length, "%d:%d:%d", $hours, $minutes, $seconds);
        return isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
    }

    public function getMillisecondsAttribute() {
        return $this->seconds * 1000;
    }
}
