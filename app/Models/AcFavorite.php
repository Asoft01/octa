<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcFavorite extends Model {

    public function content() {
        return $this->belongsTo('App\Models\AcContent');
    }

    public function user() {
        return $this->belongsTo('App\Models\Auth\User');
    }

}
