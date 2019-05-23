<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DeliveryTicket extends Model
{
    protected $fillable =['company_id', 'area_id', 'salesrep_id', 'status', 'ticket_number', 
                          'delivery_date', 'purchase_order_number', 'ordered_by', 
                          'delivered_by'];
    protected $dates = ['delivery_date'];

    public function company()
    {
        return $this->belongsTo('Arrow\Company');
    }

    public function salesRep()
    {
        return $this->belongsTo('Arrow\SalesRep','salesrep_id');
    }

    public function items()
    {
        return $this->hasMany('Arrow\DeliveryTicketItem');
    }

    public function nextTicketNumber()
    {
        if($this->ticket_number)
        {
            $number = explode('-', $this->ticket_number);
            $lastFour = (int)$number[1];
            return $number[0].'-'.sprintf("%04s", ++$lastFour);
        }
        return $this->salesRep->code.'-0001';
    }

    /**
     * Returns a collection of Delivery Tickets for a field within a 
     * date range.
     * 
     * @param  Arrow\Field  $field
     * @param  Object       $dates  contains: $dates->start_date, $dates->end_date
     */
    public static function inRangeForField($field, $dates)
    {
        $start_date = new Carbon($dates->start_date);
        $end_date = new Carbon($dates->end_date);

        return $field->area
                     ->deliveryTickets()
                     ->whereBetween('delivery_date', [$start_date, $end_date])->get();
    }
}
