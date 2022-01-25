<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcProduct extends Model
{
	public function account() {
		return $this->belongsTo('App\Models\AcAccount');
	}

	public function unit()
    {
        return $this->belongsTo('App\Models\AcUnit');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\AcLanguage');
    }

    public function family()
    {
        return $this->belongsTo('App\Models\AcProductFamily');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\AcCurrency');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\AcPrice', 'product_id');
    }

    public function orderitems()
    {
        return $this->hasMany('App\Models\AcOrderItem', 'product_id');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Models\AcDelivery', 'product_id');
    }

    public function getTypeAttribute(){
        if($this->isLive) {
            return 'Live';
        }else  if($this->isStream){
            return 'Stream';
        }else{
            return 'Pre-recorded';
        }
    }

}
