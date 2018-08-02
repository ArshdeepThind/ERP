<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';
    protected $fillable = [
        "product_id","supplier_name","supplier_category"
    ];
}
