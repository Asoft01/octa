<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Traits\ScopeWhereHasIn;

class AcContent extends Model
{
	use ScopeWhereHasIn;

	protected $with = ['categories', 'contentable'];

    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany('App\Models\AcCategory', 'ac_category_contents');
    }

    public function contentable()
    {
        return $this->morphTo();
    }

	public function favorites()
    {
        return $this->hasMany('App\Models\AcFavorite', 'content_id');
    }
	
	public function watchlistitems()
    {
        return $this->hasMany('App\Models\AcWatchlistItem', 'content_id');
    }

	public function votes()
    {
        return $this->hasMany('App\Models\AcContentUserVote', 'content_id');
    }

	public function metrics()
    {
        return $this->hasMany('App\Models\AcContentUserMetric', 'content_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\AcTag', 'ac_content_tags');
    }

    public function delivery()
    {
        return $this->hasOne('App\Models\AcDelivery', 'id', 'delivery_id');
    }

    public function categoryDomains()
    {
        return $this->hasManyThrough(
            'App\Models\AcCategoryDomain',
            'App\Models\AcCategoryContent',
            'ac_content_id',
            'ac_category_id',
            'id',
            'ac_category_id'
        );
    }

    public function getContentTypeAttribute() {
        return Str::after($this->contentable_type, 'Morph');
    }

    public function getFullNameAttribute()
    {
        return $this->getContributor();
        // if (isset($this->contentable) && isset($this->contentable->mentor) && isset($this->contentable->mentor->user)) {
        //     return $this->getContributor();
        // }
        // return null;
    }

    public function getContributor()
    {
        if($this->contentable->mentor->user->last_name == "_") {
            return $this->contentable->mentor->user->first_name;
        } else {
            return $this->contentable->mentor->user->first_name . " " . $this->contentable->mentor->user->last_name;
        }
    }

    public function getContributorUrl()
    {
        $slug = $this->contentable->mentor->slug;
        if ($slug) {
            $roles = $this->contentable->mentor->user->roles;
            if ($roles->contains('name', 'mentor')) {
                return route('frontend.reviewer', $slug);
            }
            if ($roles->contains('name', 'contributor')) {
                return route('frontend.contributor', $slug);
            }
        }
        return null;
    }

    public function getDescription()
    {
        // the idea was to simply detect links and put an ahref... but since users are putting html markup... we removed it...
       // $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
       // $string = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $this->description);
        //return nl2br($string);
        return $this->description;
    }

	public function getImage($cotd = false) {
        if ($this->isPlaylist()) {
            return $this->contentable->getImage($cotd);
        }
        if ($cotd) {
            return $this->contentable->poster_cotd ?: $this->contentable->poster;
        }
		return $this->contentable->poster;
	}

	public function getImageUrl() {
		$path = $this->getImage();
		return $path ? config('ac.SIH').config('ac.THUMB_RES').$path : null;
	}

    public function getCat() {
        $final = array();
        foreach($this->categories->where('id', '!=', '1')->pluck('title')->toArray() as $cat) {
            array_push($final, '<a href="' . route('frontend.category', $cat) . '">' . $cat . '</a>');
        }
        return implode(" | ", $final);
    }

    public function getCatNC() {
        return implode(" | ", $this->categories->where('id', '!=', '1')->pluck('title')->toArray());
    }

	public function isPlaylist() {
		return ($this->contentable_type == 'MorphPlaylist');
    }

    public function isReview() {
        $cid = $this->categories->where('id', '!=', '1')->pluck('id')->toArray();
        if(in_array(10, $cid)) {
            return true;
        } else {
            return false;
        }
    }

	/**
     * Scope a query to only include content that morphs
	 * to a model that belongs to a specified account.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  \App\Models\AcAccount|integer  $account
	 * @param  array|string  $morphs [optional]
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereAccount($query, $account, $morphs = '*') {
		$key = optional($account)->getKey() ?: $account;
		return $query->whereHasMorph('contentable', $morphs, function ($query) use ($key) {
			$query->whereHas('mentor', function ($query) use ($key) {
				$column = $query->qualifyColumn($query->getModel()->getKeyName());
				$query->where($column, $key);
			});
		});
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
		return $query->whereHas('categories.domains', function ($query) use ($key) {
			$column = $query->qualifyColumn($query->getModel()->getKeyName());
			$query->where($column, $key);
		});
    }

	/**
     * Scope a query to only include content that has
	 * a specified tag.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  \App\Models\AcTag|integer  $tag
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereTag($query, $tag) {
		$key = optional($tag)->getKey() ?: $tag;
		return $query->whereHas('tags', function ($query) use ($key) {
			$column = $query->qualifyColumn($query->getModel()->getKeyName());
			$query->where($column, $key);
		});
    }

	/**
     * Scope a query to only include content that is ready for public viewing.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  \Illuminate\Support\Carbon  $date  [optional]
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsAvailable($query, $date = null) {
		$isPublic = $query->qualifyColumn('isPublic');
		$display_start = $query->qualifyColumn('display_start');
        return $query->where($isPublic, 1)->where($display_start, '<=', $date ?: now());
    }

	/**
     * Scope a query to order by the default display order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $direction  [optional]
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisplayOrder($query, $direction = 'desc') {
		$display_start = $query->qualifyColumn('display_start');
        return $query->orderBy($display_start, $direction); // ->orderBy('created_at', $direction);
    }
}
