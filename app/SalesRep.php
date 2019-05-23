<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class SalesRep extends Model
{
    protected $fillable = ['user_id', 'code'];

    public function user()
    {
        return $this->belongsTo('Arrow\User');
    }

    public function getLastDeliveryTicketattribute()
    {
        return $this->deliveryTickets()->orderBy('ticket_number', 'desc')->first();
    }

    public function deliveryTickets()
    {
        return $this->hasMany('Arrow\DeliveryTicket','salesrep_id');
    }
}
