<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $fillable = [
        "title_en","title_ar","slug",'description_en','description_ar','status'
    ];
   
}
