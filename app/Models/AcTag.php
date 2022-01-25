<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcTag extends Model {

	protected $guarded = [];

	public function accounts() {
		return $this->belongsToMany('App\Models\AcAccount', 'ac_account_tags');
	}

	public function contents() {
		return $this->belongsToMany('App\Models\AcContent', 'ac_content_tags')->where('display_start', '<=', now())->orderBy('display_start', 'DESC');
	}
	
	public function domain() {
		return $this->belongsToMany('App\Models\AcDomain');
	}

	public function getSlugAttribute() {
		return urlencode(mb_strtolower($this->title));
	}

}
