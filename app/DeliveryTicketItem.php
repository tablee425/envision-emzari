<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class DeliveryTicketItem extends Model
{
    protected $fillable = ['delivery_ticket_id', 'location_id', 'chemical', 
                           'injection_type', 'quantity', 'packaging'];

    public function deliveryTicket()
    {
        return $this->belongsTo('Arrow\DeliveryTicket');
    }

    public function chemical()
    {
        return $this->belongsTo('Arrow\Chemical');
    }

    public function location()
    {
        return $this->belongsTo('Arrow\Location');
    }

    public function applicableInjection()
    {
        // $ticketDate = $this->deliveryTicket->purchase_date;
        // $injection = $this->location->injections()
        //                             ->where('name', $this->chemical)
    }
}
