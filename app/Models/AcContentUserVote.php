<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcContentUserVote extends Model {

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'user_id',
		'content_id'
	];

    public function content() {
        return $this->belongsTo('App\Models\AcContent');
    }

    public function user() {
        return $this->belongsTo('App\Models\Auth\User');
    }

}
