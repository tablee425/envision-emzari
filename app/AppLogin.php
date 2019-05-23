<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class AppLogin extends Model
{
    //
    protected $fillable = [
        'share_name',
        'share_price',
        'share_qty'
    ];
}
