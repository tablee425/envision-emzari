<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = [];

    public function fields()
    {
        return $this->hasMany('Arrow\Field');
    }
    public function company()
    {
        return $this->belongsTo('Arrow\Company');
    }
    public function users()
    {
        return $this->belongsToMany('Arrow\User');
    }

    public function closeouts()
    {
        return $this->hasManyThrough('Arrow\Closeout', 'Arrow\Field');
    }

    public function deliveryTickets()
    {
        return $this->hasMany('Arrow\DeliveryTicket');
    }


}
