<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Chemical extends Model
{
    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo('Arrow\Location');
    }
}
