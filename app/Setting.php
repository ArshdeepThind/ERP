<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    

    protected $fillable = [
        'label','value','created_at'
    ];

    protected $dates = ['created_at'];

}
