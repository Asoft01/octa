<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcDelivery extends Model
{

    protected $guarded = [];
    protected $dates = ['due_date', 'completed_date', 'created_at', 'updated_at' ];
    

    public function order_item()
    {
        return $this->belongsTo('App\Models\AcOrderItem');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\AcOrder');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\AcInit');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }

    public function reviewer()
    {
        return $this->belongsTo('App\Models\AcAccount');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\AcProduct');
    }

    public function family()
    {
        return $this->belongsTo('App\Models\AcProductFamily');
    }

    public function content()
    {
        return $this->hasOne('App\Models\AcContent', 'delivery_id');
    }
}
