<?php

namespace App;

use App\Notifications\AdminResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;
    
    const ADMIN_DEFAULT_PIC= 'avatar.png';

    protected $table = 'admins';
    /**
     * Setting up default value to be set while
     * 
     */
    protected $attributes = [
        'pic'    => self::ADMIN_DEFAULT_PIC,
        "status" => 'active'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }

    public function pages(){
        return $this->hasMany(Pages::class);
    }
    
}