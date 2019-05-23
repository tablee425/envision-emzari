<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;
use Arrow\Production;
use Arrow\DeliveryTicketItem;
use Carbon\Carbon;
use Session;

class Injection extends Model
{
    const TYPE_BATCH = 'BATCH';
    const TYPE_CONTINUOUS = 'CONTINUOUS';

    const STATUS_READ_ONLY = 1;
    const STATUS_ENABLED = 0;

    protected $guarded = [];
    protected $dates = ['date', 'start_date'];


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'uwi' => 'unique:injections|nullable|max:255',
        ];
    }

    public static function errorMessages()
    {
        return [
            'uwi' => 'The uwi has already been taken!',
        ];
    }

    public function location()
    {
        return $this->belongsTo('Arrow\Location');
    }

    public function ticketItems(Carbon $startDate, Carbon $endDate)
    {
        return DeliveryTicketItem::join('delivery_tickets', 'delivery_tickets.id', '=', 'delivery_ticket_items.delivery_ticket_id')
                                 ->whereBetween('delivery_tickets.date', [$startDate, $endDate])
                                 ->where('chemical', $this->name)
                                 ->where('injection_type', strtolower($this->type))->get();

    }

    /**
     * This relation only works when the product_id is joined to the Injection table
     * like in ReportController. Might want to make the column.
     */
    public function production()
    {
        $date = $this->date ?? Carbon::now()->format('Y-m').'-01';
        return $this->belongsTo('Arrow\Production')->withDefault(['location_id' => $this->location_id, 'date' => $date]);
    }

    public function getProduction()
    {
        $production = Production::where('location_id', $this->location_id)->where(\DB::raw("CONCAT(DATE_FORMAT(date, '%Y-%m'), '-1')"), (new Carbon($this->date))->format('Y-m').'-1')->first();
        if (! $production)
        {
            $date = $this->date ? $this->date->format('Y-m') : null;
            $production = $this->location_id ? Production::buildNull($this->location_id, $date.'-01') : new Production;
        }
        return $production;
    }

    public function chemicalUsed()
    {
        return $this->chemical_start + $this->chemical_delivered - $this->chemical_end;
    }

    public function usageRate()
    {
        $days = $this->days_in_month ?: 30;
        // return ($this->usage_rate != null) ? $this->usage_rate : round($this->chemicalUsed() / $days, 2);
        return round($this->chemicalUsed() / $days, 2);
    }

    public function actualPPM()
    {
        if ($this->actual_ppm) return $this->actual_ppm;

        if ($this->production->{"avg_$this->based_on"} != 0)
            return (int)($this->usageRate() * 1000 / $this->production->{"avg_$this->based_on"});
        return 0;
    }

    public function estimateUsageRate()
    {
        if ($this->estimate_usage_rate) {
            return $this->estimate_usage_rate;
        }

        $intervalMonth = Carbon::now()->diffInMonths(Carbon::parse($this->date));
        if ($intervalMonth === 1 && $this->usageRate()) {
            return $this->usageRate();
        }
    }


    public function daysRemaining()
    {
        if ($this->usageRate())
        return round($this->chemical_end / $this->usageRate(), 2);
    }

    public function overUnder()
    {
        // $calc = $this->usageRate() - $this->targetRate();
        $calc = number_format($this->usageRate() - (float)$this->vendor_target, 2 ,'.', '');
        return ($calc > 0) ? $calc : 0;
    }

    public function overCost()
    {
        return round($this->overUnder() * $this->unit_cost * 0.01 * $this->days_in_month, 2);
    }

    public function vendorBudget()
    {
        return number_format((float)$this->vendor_target * $this->days_in_month * $this->unit_cost * 0.01, 2, '.', '');
    }

    public function targetRate()
    {
        return ($this->target_rate != null) ? $this->target_rate : $this->target_ppm * $this->production->rate($this->based_on) / 1000;
    }

    public function targetBudget()
    {
        return number_format($this->targetRate() * $this->days_in_month * $this->unit_cost * 0.01, 2, '.', '');
    }

    public function actualRate()
    {
        return number_format($this->usageRate() * $this->days_in_month * $this->unit_cost * 0.01, 2, '.', '');
    }

    public function totalMonthlyCost()
    {
        return number_format($this->usageRate() * $this->unit_cost * $this->days_in_month * 0.01, 2, '.', '');
    }

    public function targetMonthlyChemicalCost()
    {
        return number_format($this->vendor_target * $this->unit_cost * $this->days_in_month * 0.01, 2, '.', '');
    }

    // Batch Methods

    public function batchCost()
    {
        return number_format($this->batch_size * $this->unit_cost * 0.01, 2, '.', '');
    }

    public function targetCost()
    {
        return number_format($this->target_frequency * $this->batch_size * $this->unit_cost * 0.01, 2, '.', '');
    }

    public function overInjectionCost()
    {
        return $this->batchCost() * ($this->scheduled_batches - $this->target_frequency);
    }

    public function underInjectionCost()
    {
        // return ($this->batchCost() - $this->targetCost()) ? 0 : 1;
        return ($this->scheduled_batches < $this->target_frequency) ? 1 : 0;
    }

    public function targetMonthlyCost()
    {
        return $this->targetRate() * $this->days_in_month * $this->unit_cost;
    }

    public function monthlyCostVariance()
    {
        return $this->totalMonthlyCost() - $this->targetMonthlyChemicalCost();
    }

    public function corrosionInhibitorRatio()
    {
        $avg_water = $this->production->avg_water != 0 ? $this->production->avg_water : rand(2, 30);
        return ($this->chemical_type == "corrosion_inhibitor") ? ($this->batch_size * $this->scheduled_batches / $avg_water) / 30 : '';
    }

    public function paraffinRatio()
    {
        $avg_oil = $this->production->avg_oil != 0 ? $this->production->avg_oil : rand(2, 30);
        return ($this->chemical_type == "demulsifier") ? ($this->batch_size * $this->scheduled_batches / $avg_oil) / 30 : '';
    }

    /**
     * @param $estimateUsageRate
     * @param $chemicalStart
     * @param $chemicalDelivered
     * @return float|int
     */
    public function calculateEstimateInventory($estimateUsageRate, $chemicalStart, $chemicalDelivered)
    {
        $numberDay = Carbon::now()->day;
        $estimateInventory = round(((float)$chemicalStart + (float)$chemicalDelivered) - ($numberDay * (float)$estimateUsageRate), 2);
        if ($estimateInventory < 0) {
            return 0;
        }
        return $estimateInventory;
    }

    /**
     * @param $injectionsContinous
     * @param $injectionsBatch
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function closeOut($injectionsContinuous, $injectionsBatch)
    {
        // if ($injectionsContinuous->count()) {
        $closeOutContinuous = self::closeOutContinous($injectionsContinuous);
            // if (!$closeOutContinious) {
            //     return redirect()->back();
            // }
        // }if ($injectionsBatch->count()) {
        $closeOutBatch = self::closeOutBatch($injectionsBatch);
            // if (!$closeOutBatch) {
            //
            // }
        //}
        if(!($closeOutContinuous && $closeOutBatch)) return redirect()->back();

        Session::flash('okInjections', "Successfully!");
        return redirect()->back();

    }

    public static function closeOutContinuous($injections)
    {
        $errors = [];
        foreach ($injections as $injection)
        {
            if (empty($injection->chemical_end))
            {
                $errors[] = [
                    'locationName' => $injection->location->name,
                    'locationId' => $injection->location_id,
                    'injectionName' => $injection->name
                ];
            }
        }

        if($errors)
        {
            Session::flash('errorContinuous', $errors);
            return false;
        }

        foreach($injections as $injection)
        {

            $newInjection = new Injection();
            $date = $injection->date->addMonth(); // date('Y-m');

            $newInjection->location_id = $injection->location->id;
            $newInjection->type = self::TYPE_CONTINUOUS;
            $newInjection->status = self::STATUS_ENABLED;
            $newInjection->chemical_start = $injection->chemical_end;
            $newInjection->date = $date; // Carbon::createFromFormat('Y-m', $date);
            $newInjection->chemical_type = $injection->chemical_type;
            $newInjection->name = $injection->name;
            $newInjection->days_in_month = (int)$date->format('t');
            $newInjection->based_on = $injection->based_on;
            $newInjection->inventory_start = $injection->chemical_end;
            $newInjection->target_ppm = $injection->target_ppm;
            $newInjection->vendor_target = $injection->vendor_target;
            $newInjection->min_rate = $injection->min_rate;
            $newInjection->unit_cost = $injection->unit_cost;
            $newInjection->cost_centre = $injection->cost_centre;
            $newInjection->estimate_usage_rate = $injection->usageRate();
            $newInjection->start_date = $injection->start_date;
            $newInjection->tank_capacity = $injection->capacity;
            $newInjection->save();

            $injection->status = self::STATUS_READ_ONLY;
            $injection->chemical_delivered = $injection->chemical_delivered ?: 0;
            $injection->usage_rate = $injection->usageRate();
            $injection->save();
        }

        return true;

    }

    /**
     * @param $injections
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public static function closeOutBatch($injections)
    {
        // $errors = [];
        // foreach ($injections as $injection)
        // {
        //     $location = $injection->location;
        //     if (null === $injection->scheduled_batches) {
        //         $errors[] = [
        //             'locationName' => $location->name,
        //             'locationId' => $injection->location_id,
        //             'injectionName' => $injection->name
        //         ];
        //     }
        // }
        // if ($errors)
        // {
        //     Session::flash('errorBatch', $errors);
        //     return false;
        // }

        foreach ($injections as $injection)
        {
            $newInjection = new Injection();
            $date = $injection->date->addMonth(); // date('Y-m');

            $newInjection->location_id = $injection->location->id;
            $newInjection->type = self::TYPE_BATCH;
            $newInjection->status = self::STATUS_ENABLED;
            $newInjection->date = $date;
            $newInjection->chemical_type = $injection->chemical_type;
            $newInjection->name = $injection->name;
            $newInjection->batch_size = $injection->batch_size;
            $newInjection->circulation_time = $injection->circulation_time;
            $newInjection->diluent_required = $injection->diluent_required;
            $newInjection->unit_cost = $injection->unit_cost;
            $newInjection->target_frequency = $injection->target_frequency;
            $newInjection->cost_centre = $injection->cost_centre;
            $newInjection->usage_rate = $injection->usage_rate;
            $newInjection->tank_capacity = $injection->capacity;
            $newInjection->save();

            $injection->status = self::STATUS_READ_ONLY;
            $injection->chemical_delivered = $injection->chemical_delivered ?: 0;
            $injection->usage_rate = $injection->usageRate();
            $injection->save();

        }

        return true;
    }
    /**
     * Check the injection for a matching production, and if none is there
     * build a null production.
     *
     */
    public function guaranteeProduction()
    {
        if (! $this->production())
            Production::buildNull($this->location_id, $this->date->format('Y-m'). '-01');
    }
}
