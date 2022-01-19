<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','api_token','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token'
    ];


    public function isAdmin(){

        return ($this->type =='admin' || $this->type =='super_admin')?true:false;
    }

    public function isSuperUser(){
        return ($this->type =='super_admin' )?true:false;
    }

    public function comments(){

        return $this->hasMany(Comment::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function permissions(){

        return $this->belongsToMany(Permission::class);
    }

    public function roles(){

        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roles)
    {
        return !! $roles->intersect($this->roles)->all();
    }

    public function hasPermission($permission)
    {
        return $this->permissions->contains('name' , $permission->name) || $this->hasRole($permission->roles);
    }

}
