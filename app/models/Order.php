<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function members()
    {
        return $this->belongsTo('\App\models\Member');
    }
}
