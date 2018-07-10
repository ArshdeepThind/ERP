<?php 

namespace App\Traits;
// use App\Role;

trait HasRoles{

	public function roles(){
        return $this->belongsToMany(\App\Role::class);
    }
    
    public function assignRole($role){
        $this->roles()->save(
            \App\Role::whereName($role)->firstOrFail()
        );
    }

    public function hasRole($role){
        if(is_string($role)){
            return $this->roles->contains('name',$role);
        }
        return $role->intersect($this->roles)->count();
    }

    public function detachRole($role){
        $this->roles()->detach(
            \App\Role::whereName($role)->firstOrFail()
        );   
    }
}