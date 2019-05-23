<?php

namespace Arrow\Providers;

use Illuminate\Support\ServiceProvider;
use Arrow\Injection;
use Arrow\Pigging;
use Arrow\DeliveryTicket;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*',"Arrow\Http\ViewComposers\MenuViewComposer");

        Injection::created(function($injection) { 
            $injection->guaranteeProduction();
        });

        // DeliveryTicket::creating(function($ticket) {
        //     if($lastTicket = $ticket->salesRep->lastDeliveryTicket)
        //     {
        //         $ticket->ticket_number = $lastTicket->nextTicketNumber(); 
        //     }
        //     else
        //     {
        //         $ticket->ticket_number = $ticket->salesRep->code.'-0001';
        //     }
        // });
        
        Pigging::saved(function($pigging) {
            $dirty = array_keys($pigging->getDirty());
            // if (($pigging->cancelled_on || $pigging->pulled_on) && (in_array("cancelled_on", $dirty) || in_array("pulled_on", $dirty)))
            if (($pigging->shipped_on && in_array("shipped_on", $dirty)) || ($pigging->cancelled_on && in_array("cancelled_on", $dirty)))
            {
                Pigging::firstOrCreate(['start_location_id' => $pigging->start_location_id,
                    'end_location_id' => $pigging->end_location_id,
                    'scheduled_on' => $pigging->scheduled_on->addDays($pigging->frequency),
                    'order' => $pigging->order,
                    'field_operator' => $pigging->field_operator,
                    'frequency' => $pigging->frequency,
                    'od' => $pigging->od,
                    'thickness' => $pigging->thickness,
                    'length' => $pigging->length,
                    'license' => $pigging->license,
                    'line_type' => $pigging->line_type,
                    'MOP' => $pigging->MOP,
                    'pressure_switch' => $pigging->pressure_switch,
                    'line_pressure' => $pigging->line_pressure,
                    'pig_size' => $pigging->pig_size]);
            }
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
