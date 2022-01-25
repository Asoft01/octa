<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcCategory extends Model
{

    // protected $with = ['contents'];

    protected $guarded = [];

    public function contents()
    {
        return $this->belongsToMany('App\Models\AcContent', 'ac_category_contents')->where('display_start', '<=', now())->where('isPublic', 1)->orderBy('display_start', 'DESC');
    }

    public function cotd()
    {
        return $this->belongsToMany('App\Models\AcContent', 'ac_category_contents')->where('cotd_start', '<=', now())->where('isPublic', 1)->orderBy('cotd_start', 'DESC');
    }

    //
    // One level child
    public function child() {
        return $this->hasMany('App\Models\AcCategory', 'parent_id');
    }

    // Recursive children
    public function children() {
        return $this->hasMany('App\Models\AcCategory', 'parent_id')->with('children');
    }

	public function domains() {
		return $this->belongsToMany('App\Models\AcDomain', 'ac_category_domains');
	}

    // One level parent
    public function parent() {
        return $this->belongsTo('App\Models\AcCategory', 'parent_id');
    }

    // Recursive parents
    public function parents() {
        return $this->belongsTo('App\Models\AcCategory', 'parent_id')->with('parent');
    }
	
	/**
     * Scope a query to only include content that is in
	 * a category that is in a specified domain.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  \App\Models\AcDomain|integer  $domain
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereDomain($query, $domain) {
		$key = optional($domain)->getKey() ?: $domain;
		return $query->whereHas('domains', function($subquery) use ($key) {
			$column = $subquery->qualifyColumn($subquery->getModel()->getKeyName());
			$subquery->where($column, $key);
		});
    }
}
