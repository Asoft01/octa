<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcPayment extends Model
{
    public function order()
    {
        return $this->belongsTo('App\Models\AcOrder');
    }

    public function payment_type()
    {
        return $this->belongsTo('App\Models\AcPaymentType');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }
}
