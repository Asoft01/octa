<?php

namespace App\Models\Auth\Traits\Relationship;

use App\Models\Auth\PasswordHistory;
use App\Models\Auth\SocialAccount;
use App\Models\AcAccount;
use App\Models\AcOrder;
use App\Models\AcHyvor;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function providers()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }


    // AC
    public function account()
    {
        return $this->hasOne('App\Models\AcAccount');
    }

	public function favorites()
    {
        return $this->hasMany('App\Models\AcFavorite', 'user_id');
    }

	public function favoriteContents()
    {
		return $this->belongsToMany('App\Models\AcContent', 'ac_favorites', 'user_id', 'content_id');
    }

	public function watchlist()
    {
        return $this->hasMany('App\Models\AcWatchlistItem', 'user_id');
    }

	public function votes()
    {
        return $this->hasMany('App\Models\AcContentUserVote', 'user_id');
    }

	public function metrics()
    {
        return $this->hasMany('App\Models\AcContentUserMetric', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\AcOrder');
    }


    public function deliveries()
    {
        return $this->hasMany('App\Models\AcDelivery', 'user_id');
    }


    public function payments()
    {
        return $this->hasMany('App\Models\AcPayment', 'user_id');
    }


    public function comments()
    {
        return $this->hasMany('App\Models\AcHyvor', 'user_id');
    }

}
