<?php

namespace Arrow;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active_company'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    
    public function companies()
    {
        return $this->belongsToMany('Arrow\Company');
    }

    public function areas()
    {
        return $this->belongsToMany('Arrow\Area');
    }

    public function activeCompany()
    {
        return ($this->active_company) ? Company::find($this->active_company) : $this->companies()->first();
    }

    public function isAdmin()
    {
        return $this->admin ? true : false;
    }
}
