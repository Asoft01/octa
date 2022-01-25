<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcOrderItem extends Model
{
    public function order()
    {
        return $this->belongsTo('App\Models\AcOrder');
    }

    public function price()
    {
        return $this->belongsTo('App\Models\AcPrice');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\AcProduct');
    }

    public function family()
    {
        return $this->belongsTo('App\Models\AcProductFamily');
    }

    public function delivery()
    {
        return $this->hasOne('App\Models\AcDelivery', 'order_item_id');
    }

    public function reviewer()
    {
        return $this->belongsTo('App\Models\AcAccount', 'reviewer_id');
    }
}
