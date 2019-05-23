<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    //
    protected $fillable = [
        'token',
        'email',
        'name'
    ];
}
