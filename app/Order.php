<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

	protected $table = 'order_customer';

    public function user() {
     return $this->belongsTo('App\User');
    }
}
