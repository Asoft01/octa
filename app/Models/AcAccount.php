<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\ScopeWhereHasIn;

class AcAccount extends Model
{
    use ScopeWhereHasIn;

    protected $dates = ['bookeduntil', 'created_at', 'updated_at' ];
    protected $with = ['user'];
    protected $guarded = [];

	public function domain() {
		return $this->belongsTo('App\Models\AcDomain', 'ac_domain_id');
	}

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\AcReview', 'mentor_id');
    }

    public function videos()
    {
        return $this->hasMany('App\Models\AcVideo', 'mentor_id');
    }

    public function languages()
    {
        return $this->belongsToMany('App\Models\AcLanguage', 'ac_account_languages');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\AcTag', 'ac_account_tags');
    }

    public function products()
    {
        return $this->hasMany('App\Models\AcProduct', 'account_id');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Models\AcDelivery', 'reviewer_id');
    }

    public function getFullNameAttribute()
    {
        return $this->getContributor();
    }

    public function getUrlAttribute() {
        if ($this->slug) {
            if ($this->user->roles->contains('name', 'mentor')) {
                return route('frontend.reviewer', $this->slug);
            }
            if ($this->user->roles->contains('name', 'contributor')) {
                return route('frontend.contributor', $this->slug);
            }
        }
        return null;
    }

    public function getContributor()
    {
        if($this->user->last_name == "_") {
            return $this->user->first_name;
        } else {
            return $this->user->first_name . " " . $this->user->last_name;
        }
    }

    public function scopeWhereRoles($query, $roles) {
        $query->whereHasIn('user.roles', $roles, 'name');
    }
}
