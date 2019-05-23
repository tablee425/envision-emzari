<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;
use Arrow\Field;
use Arrow\Injection;
use Arrow\Closeout;
use Arrow\DeliveryTicket;
use Arrow\DeliveryTicketItem;
use DB;
use Carbon\Carbon;
use Session;

use Arrow\Http\Requests;

class CloseOutController extends Controller
{
    public $injectionIDsWithTickets = []; // Used for processing Batch Closeouts

    public function index(Request $request, Field $field)
    {
        $this->authorize('update', $field);
        // Get injections for Month
        $continuousInjections = $this->_fetchInjections($field, Injection::TYPE_CONTINUOUS);
        $batchInjections = $this->_fetchInjections($field, Injection::TYPE_BATCH);

        return view('closeout.index', compact('continuousInjections', 'batchInjections'));

    }

    /**
     * Show form for getting closeout dates.
     * @return Illuminate\Response
     */
    public function datePicker(Request $request, Field $field)
    {
        $priorCloseout = $field->closeOuts()->orderBy('end_date','desc')->first();
        $start_date = $priorCloseout ? $priorCloseout->closeout_date : null;
        $end_date = null;

        return view('closeout.date-picker', compact('field','start_date','end_date'));
    }

    public function process(Request $request)
    {
        // Get injections for each type
        // return dd($request->all());
        \Log::info('Processing closeout.');
        $closeOuts = [];
        $field = Field::find($request->field_id);
        $success = true;
        collect(['CONTINUOUS', 'BATCH'])->each(function($injection_type) use ($request, $closeOuts, $field, &$success) {

            $injections = $this->_fetchInjections($field, $injection_type);
            $closeOuts[$injection_type] = $injections;
            // 1. Find tickets related to those injections
            $tickets = DeliveryTicket::inRangeForField($field, $request);
            // 2. Get the ticket items for all those tickets
            $items = DeliveryTicketItem::whereIn('delivery_ticket_id', $tickets->pluck('id'))
                                        ->where('injection_type', strtolower($injection_type))
                                        ->get();
            // 3. Apply items to injections
            $this->_applyTicketChemicalToInjections($injections, $items);

            // Close out batch conditional, or normal close out.
            if($injection_type == 'BATCH' && $request->batch_option == 'delivery_tickets')
            {
                \Log::info('Importing batch delivery tickets');
                $batchInjections = $closeOuts['BATCH'];
                // Check if these injections are prepared to close.
                // REMOVED SCANNING BATCH INJECTIONS
                // Need to zero out next month for injections without tickets
                $batchInjections->each(function($injection) {
                    \Log::info('Handling Batch Injection ID: '. $injection->id);
                    \Log::info('IDs with tickets');
                    \Log::info($this->injectionIDsWithTickets);
                    \Log::info('ID: '. $injection->id);
                    if(! in_array($injection->id, $this->injectionIDsWithTickets))
                    {
                        // If it doesn't have a ticket, zero out the closeout.
                        $this->_zeroOutBatchCloseOut($injection);
                    }
                    else
                    {
                        // Otherwise use delivery ticket totals to close out.
                        $this->_applyDeliveryTicketBatchCloseOut($injection);
                    }
                });
                // Assign deliveries to batch sizes
            } else {
                $closeOut = 'closeOut'.ucfirst(strtolower($injection_type));
                if (! Injection::$closeOut($injections)) $success = false;
            }
        });

        if($success)
        {
            Closeout::create(['field_id' => $field->id, 'start_date' => (new Carbon($request->start_date))->format('Y-m-d'),
                              'end_date' => (new Carbon($request->end_date))->format('Y-m-d')]);
            Session::flash('okInjections', "Successfully!");
        }
        return redirect('fields?area_id='. $field->area_id);
    }

    // Return Batch or Continuous Injections for Current Month
    protected function _fetchInjections(Field $field, $type)
    {
        return $field->injections()->where(
            \DB::raw("DATE_FORMAT(date, '%Y-%m')"),
            date('Y-m', strtotime(date('Y-m')." -1 month")))
            ->where('status', '=', Injection::STATUS_ENABLED)
            ->where('type', '=', $type)
            ->get();
    }

    // Get Delivery Tickets in date range for current field.
    protected function _getDeliveryTicketsInRangeForField(Field $field, $request)
    {
        $start_date = new Carbon($request->start_date);
        $end_date = new Carbon($request->end_date);

        return $field->area
                     ->deliveryTickets()
                     ->whereBetween('delivery_date', [$start_date, $end_date])->get();
    }

    // Apply ticket items to locations from related injection
    protected function _applyTicketChemicalToInjections($injections, $items)
    {
        $injections->each(function($injection) use ($items) {
            $totalDelivered = $items->where('chemical', $injection->name)
                                    ->where('location_id', $injection->location_id)
                                    ->sum('quantity');
            $injection->update(['chemical_delivered' => $totalDelivered]);
            if($items->where('chemical', $injection->name)
                     ->where('location_id', $injection->location_id)->count() > 0)
            {
                $this->injectionIDsWithTickets[] = $injection->id;
            }
        });
    }

    protected function _zeroOutBatchCloseOut($injection)
    {
        \Log::info('Zeroing out new injection for injection ID: '. $injection->id);
        $newInjection = new Injection();
        $date = $injection->date->addMonth(); // date('Y-m');

        $newInjection->location_id = $injection->location->id;
        $newInjection->type = Injection::TYPE_BATCH;
        $newInjection->status = Injection::STATUS_ENABLED;
        $newInjection->date = $date;
        $newInjection->chemical_type = $injection->chemical_type;
        $newInjection->name = $injection->name;
        $newInjection->batch_size = 0;
        $newInjection->scheduled_batches = 0;
        $newInjection->circulation_time = $injection->circulation_time;
        $newInjection->diluent_required = $injection->diluent_required;
        $newInjection->unit_cost = $injection->unit_cost;
        $newInjection->target_frequency = $injection->target_frequency;
        $newInjection->cost_centre = $injection->cost_centre;
        $newInjection->usage_rate = $injection->usage_rate;
        $newInjection->tank_capacity = $injection->capacity;
        $newInjection->save();

        $injection->status = Injection::STATUS_READ_ONLY;
        $injection->chemical_delivered = $injection->chemical_delivered ?: 0;
        $injection->batch_size = 0;
        $injection->scheduled_batches = 0;
        $injection->usage_rate = $injection->usageRate();
        $injection->save();
    }

    protected function _applyDeliveryTicketBatchCloseOut($injection)
    {
        \Log::info('Importing deliveries for new injection for injection ID: '. $injection->id);
        $newInjection = new Injection();
        $date = $injection->date->addMonth(); // date('Y-m');

        $newInjection->location_id = $injection->location->id;
        $newInjection->type = Injection::TYPE_BATCH;
        $newInjection->status = Injection::STATUS_ENABLED;
        $newInjection->date = $date;
        $newInjection->chemical_type = $injection->chemical_type;
        $newInjection->name = $injection->name;
        $newInjection->batch_size = 0;
        $newInjection->scheduled_batches = 0;
        $newInjection->circulation_time = $injection->circulation_time;
        $newInjection->diluent_required = $injection->diluent_required;
        $newInjection->unit_cost = $injection->unit_cost;
        $newInjection->target_frequency = $injection->target_frequency;
        $newInjection->cost_centre = $injection->cost_centre;
        $newInjection->usage_rate = $injection->usage_rate;
        $newInjection->tank_capacity = $injection->capacity;
        $newInjection->save();

        $injection->status = Injection::STATUS_READ_ONLY;
        $injection->chemical_delivered = $injection->chemical_delivered ?: 0;
        
        $injection->batch_size = $injection->chemical_delivered;
        $injection->scheduled_batches = 1;
        
        $injection->usage_rate = $injection->usageRate();
        $injection->save();
    }

    protected function _scanBatchInjections($injections)
    {
        $errors = [];
        foreach ($injections as $injection)
        {
            $location = $injection->location;
            if (null === $injection->scheduled_batches) {
                $errors[] = [
                    'locationName' => $location->name,
                    'locationId' => $injection->location_id,
                    'injectionName' => $injection->name
                ];
            }
        }
        if ($errors)
        {
            Session::flash('errorBatch', $errors);
            return false;
        }
        return true;
    }
}