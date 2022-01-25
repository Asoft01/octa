<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcProductFamily extends Model
{
    public function products()
    {
        return $this->hasMany('App\Models\AcProduct', 'product_family_id');
    }

    public function prices()
    {
        return $this->hasManyThrough('App\Models\AcPrice', 'App\Models\AcProduct', 'product_family_id', 'product_id');
    }

    public function orderitems()
    {
        return $this->hasMany('App\Models\AcOrderItem', 'product_family_id');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Models\AcDelivery', 'product_family_id');
    }

}   
