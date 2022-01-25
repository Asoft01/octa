<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcUnit extends Model
{
    public function products()
    {
        return $this->hasMany('App\Models\AcProduct', 'unit_id');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Models\AcDelivery', 'unit_id');
    }
}
