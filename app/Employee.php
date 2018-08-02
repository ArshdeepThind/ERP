<?php

namespace App;

use App\Notifications\EmployeeResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasRoles;

class Employee extends Authenticatable
{
    use Notifiable,SoftDeletes,HasRoles;
    protected $table = 'employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','phone_number','gender','marital_status','city','state','country','address1','address2','zipcode','joining_date','date_of_birth','current_salary','gross_salary','loan_amount','email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function jobs(){
        return $this->belongsToMany('App\Job')->withPivot('start_date','end_date');
    }

    public function history(){
        return $this->hasMany(Payment::class);
    }

    public function in_process_salary(){
        return $this->history()->where('is_paid','=','process');
    }


    public function paid_history(){
        return $this->history()->where('is_paid','=','paid');
    }
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new EmployeeResetPassword($token));
    }
}
