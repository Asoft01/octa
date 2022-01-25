<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AcDomain extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ac_domains';

	/**
     * The accounts that belong to the domain.
	 */
	public function accounts() {
		return $this->hasMany('App\Models\AcAccount', 'ac_domain_id');
	}

	/**
	 * The announcements category for the domain.
	 */
	public function announcements() {
		return $this->hasOne('App\Models\AcCategory', 'id', 'announcements_category_id');
	}

	/**
     * The categories that belong to the domain.
	 */
	public function categories() {
		return $this->belongsToMany('App\Models\AcCategory', 'ac_category_domains');
	}

	/**
     * The tags that belong to the domain.
	 */
	public function tags() {
		return $this->hasMany('App\Models\AcTag', 'domain_id');
	}

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName() {
		return 'slug';
	}

	/**
	 * Retrieve the model for a bound value.
	 *
	 * @param  mixed  $value
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function resolveRouteBinding($value) {
		return $this->where($this->getRouteKeyName(), $value)->first();
	}

	public function getContentIds() {
		return $this->categories()->with('contents:id')->get()->pluck('contents')->flatten(1)->pluck('id')->unique()->all();
	}

	public function getTagIds() {
		return $this->tags()->get(['id'])->pluck('id')->all();
	}

}
