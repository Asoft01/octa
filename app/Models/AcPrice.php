<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcPrice extends Model
{
    protected $with = ['product', 'currency'];

    public function product()
    {
        return $this->belongsTo('App\Models\AcProduct');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\AcCurrency');
    }


    public function orderitems()
    {
        return $this->hasMany('App\Models\AcOrderItem', 'price_id');
    }
}
