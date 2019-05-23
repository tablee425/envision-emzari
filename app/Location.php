<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = [];

    public function injections()
    {
        return $this->hasMany('Arrow\Injection');
    }

    public function production()
    {
        return $this->hasMany('Arrow\Production');
    }
    
    public function field()
    {
        return $this->belongsTo('Arrow\Field');
    }

    public function chemicals()
    {
        return $this->hasMany('Arrow\Chemical');
    }
}
