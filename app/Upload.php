<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = ['location_id', 'date', 'path', 'original_name'];
    public function uploadable()
    {
        return $this->morphTo();
    }
}
