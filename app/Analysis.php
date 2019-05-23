<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    protected $table = "analysis";
    protected $fillable = ['location_id', 'corrosion_residuals', 'scale_residuals', 'water_qualities',
                           'comments', 'date'];
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
