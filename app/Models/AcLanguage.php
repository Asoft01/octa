<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcLanguage extends Model
{
    public function accounts()
    {
        return $this->belongsToMany('App\Models\AcAccount', 'ac_account_languages');
    }

    public function products()
    {
        return $this->hasMany('App\Models\AcProduct', 'language_id');
    }
}
