<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

use Arrow\Area;

class Company extends Model
{
    protected $guarded = [];
    
    public function areas()
    {
        return $this->hasMany('Arrow\Area');
    }

    public function fields()
    {
        return $this->hasManyThrough('Arrow\Field', 'Arrow\Area');
    }

    public function locations($like = null)
    {
        $locations = collect();
        foreach ($this->fields as $field)
        {
            if($like)
            {
                foreach($field->locations as $location)
                {
                    if (strpos($location->name, $like) !== false)
                        $locations->push($location);
                }
            } else {
                foreach($field->locations as $location)
                    $locations->push($location);
            }
        }
        return $locations;
    }

    public function locationsByCostcentre($like = null, $area_id = null)
    {
        $locations = collect();
        if($area_id) $area = Area::find($area_id);
        $fields = $area_id ? $area->fields : $this->fields; 
        foreach ($fields as $field)
        {
            if($like)
            {
                foreach($field->locations as $location)
                {
                    if ((strpos($location->name, $like) !== false) || (strpos($location->cost_centre, $like) !== false))
                        $locations->push($location);
                }
            } else {
                foreach($field->locations as $location)
                    $locations->push($location);
            }
        }
        return $locations;
    }
    
    public function users()
    {
        return $this->belongsToMany('Arrow\User');
    }
}
