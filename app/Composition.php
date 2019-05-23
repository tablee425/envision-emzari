<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    protected $table = 'composition';
    protected $fillable = ['location_id', 'date', 'iron', 'manganese', 'chloride', 'comments'];
    protected $dates = ['date'];

    public function uploads()
    {
        return $this->morphMany('Arrow\Upload', 'uploadable');
    }
    public function location()
    {
        return $this->belongsTo('Arrow\Location');
    }
}
