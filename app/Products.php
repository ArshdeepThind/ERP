<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';
    
    protected $fillable = [
        "product_name","product_price","quantity_initial","quantity_shipped","quantity_left","product_category","gender","description","product_image"
    ];
}
