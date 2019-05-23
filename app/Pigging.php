<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Pigging extends Model
{
    protected $fillable = ['start_location_id', 'end_location_id', 'order', 'od', 'thickness', 'license', 'length', 'frequency', 'scheduled_on',
                           'shipped_on', 'pulled_on', 'cancelled_on', 'pig_size', 'line_pressure', 'pressure_switch',
                           'pig_number', 'gauged', 'condition', 'wax', 'diluent', 'corr_inh_vol', 'biocide_vol', 'water_vol',
                           'field_operator', 'line_type', 'MOP', 'comments'];
    protected $dates = ['scheduled_on', 'shipped_on', 'pulled_on', 'cancelled_on'];
    
    public function startLocation()
    {
        return $this->belongsTo('Arrow\Location', 'start_location_id');
    }

    public function endLocation()
    {
    	return $this->belongsTo('Arrow\Location', 'end_location_id');
    }
}