<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Production extends Model
{
    protected $table = 'production';

    protected $fillable = ['location_id', 'date', 'hours_on', 'avg_oil', 'avg_gas', 'avg_water', 'target_ppm'];

    protected $dates = ['created_at', 'updated_at', 'date'];

    public function location()
    {
        return $this->belongsTo('Arrow\Location');
    }

    public function totalProduction()
    {
        return $this->avg_oil + $this->avg_gas + $this->avg_water; 
    }

    public function batchCost()
    {
        return $this->batch_size * $this->unit_cost;
    }

    public function rate($based_on)
    {
        switch(strtolower($based_on))
        {
            case "oil"   : return $this->avg_oil;
            case "gas"   : return $this->avg_gas;
            case "water" : return $this->avg_water; 
        }
        return 0;
    }

    /**
     * We're starting to requrie a production be available for all injections,
     * whether there is actually data or not. This method builds a null production object
     * for a location and date.
     *
     * @param  $location_id  integer
     * @param  $date  string 'YYYY-MM-01'
     *
     * @return Arrow\Production
     */
    public static function buildNull($location_id, $date = null)
    {
        $date = $date ?: Carbon::now()->format('Y-m').'-01';
        return static::create(['location_id' => $location_id, 'date' => $date]);
    }
}
