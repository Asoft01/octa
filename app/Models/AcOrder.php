<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcOrder extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }

    public function status_order()
    {
        return $this->belongsTo('App\Models\AcStatus');
    }

    public function orderitems()
    {
        return $this->hasMany('App\Models\AcOrderItem', 'order_id');
    }

    public function orderitem()
    {
        return $this->hasMany('App\Models\AcOrderItem', 'order_id')->latest();
    }

    public function payments()
    {
        return $this->hasMany('App\Models\AcPayment', 'order_id');
    }

    public function delivery()
    {
        return $this->hasOne('App\Models\AcDelivery', 'order_id');
    }
}
