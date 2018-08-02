<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'employee_payment';

    protected $fillable = ['employee_id', 'salary', 'commission' ,'other_tax','paying_date'];

    protected $dates = ['created_at','updated_at'];

    public function employees(){
        return $this->belongsTo(Employee::class);
    }

}
