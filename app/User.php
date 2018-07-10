<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes,HasRoles;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','email','phone','password','gender','is_verified','pic','wallet','credit','dob','api_token','status','google_id','facebook_id','region_code'
    ];

    protected $dates = ['created_at','updated_at','deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'device_token','device_type'
    ];

    public function is_verified()
    {
        $this->is_verified = 1;
        $this->status = 1;
        $this->save();
    }

    public function drivers(){
        return $this->hasOne(Driver::class);
    }

    public function history(){
        return $this->hasMany(Wallet::class);
    }

    public function notification(){
        return $this->hasMany(Notification::class);
    }

    public function savehistory($wallet){
        $this->history()->create($wallet);
    }

    // Save Clinic details
    public function savedrivers(Driver $drivers){
        // if clinic details not available than create details
        if($this->drivers == null)
        {
            $this->drivers()->save($drivers);
        }
        else
        {
            $this->drivers()->update($drivers->toArray());
        }
    }

}
