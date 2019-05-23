<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use DB;
use Datatables;
use Auth;
use Arrow\Injection;
use Arrow\Field;
use Arrow\Location;
use Arrow\Area;
use Arrow\Production;
use Carbon\Carbon;
use Validator;
use Excel;
use Session;

class InjectionController extends Controller
{
    private function checkAuth($request) {
        if ($request->type == 'field')
        {
            parent::isAuthorized($request->id, 'fields');
        }
        elseif ($request->type == 'area')
        {
            parent::isAuthorized($request->id, 'areas');
        }
    }

    public function getContinuous(Request $request)
    {
        Session::put('saveState', $request->fullUrl());
        $this->checkAuth($request);
        $type = ucfirst($request->type);
        $chem_view = ($request->view == 'chemical') ? true : false;

        $title = $this->_getTitle($request->id, $type);
        return view('injections.continuous.index', ['id' => $request->id, 'type' => $request->type,
            'injection_title' => $title, 'injection' => Injection::find($request->id),
            'chem_view' => $chem_view ]);
    }

    public function getBatch(Request $request)
    {
        Session::put('saveState', $request->fullUrl());
        $this->checkAuth($request);
        $type = ucfirst($request->type);
        $title = $this->_getTitle($request->id, $type);
        return view('injections.batch.index', ['id' => $request->id, 'type' => $request->type,
            'injection_title' => $title, 'injection' => Injection::find($request->id)]);
    }

    public function getCreate(Request $request)
    {
        $injection = new Injection;
        $button = "Create Injection";
        $location_id = $request->location_id;
        // if(! $production = Production::where('location_id', $request->location_id)->first())
        //     return redirect()->action('ProductionController@getImport');
        $production = Production::firstOrNew(['location_id' => $request->location_id, 'date' => date('Y-m').'-01']);
        if ($request->type == 'continuous')
        {
            $action = "InjectionController@postContinuousCreate";
            return view('injections.continuous.form', compact('injection', 'production', 'action', 'button', 'location_id'));
        }
        else
        {
            $action = "InjectionController@postBatchCreate";
            return view('injections.batch.form', compact('injection', 'production', 'action', 'button', 'location_id'));
        }
    }

    public function postBatchCreate(Request $request)
    {
        $saveState = Session::get('saveState', null);
        $data = $request->all();
        // return dd($data);
        $date = Carbon::createFromFormat('Y-m', $data['date']);

        $production = Production::where('location_id', $data['location_id'])->where(
            \DB::raw("DATE_FORMAT(date, '%Y-%m')"),
            (new Carbon($data['date']))->format('Y-m'))->first();
        // return dd($production);
        if (!$production) {
            $production = new Production();
            $production->location_id = $data['location_id'];
            $production->date = $date->format('Y-m'). "-01";
            $production->save();
        }

        // $production->update(['avg_gas' => $request->avg_gas, 'avg_oil' => $request->avg_oil,
        //     'avg_water' => $request->avg_water]);
        unset($data['avg_oil']);
        unset($data['avg_gas']);
        unset($data['avg_water']);

        unset($data['date']);
        unset($data['_token']);
        unset($data['id']);

        $injection = new Injection($data);
        $injection->date = $date;
        $injection->type = "BATCH";
        $injection->save();
        return $saveState ? redirect($saveState) : redirect('injections/batch?type=location&id='. $injection->location_id);
    }

    public function postContinuousCreate(Request $request)
    {
        $saveState = Session::get('saveState', null);
        $data = $request->all();
        $date = Carbon::createFromFormat('Y-m', $data['date']);
        $startDate = Carbon::createFromFormat('m-d-Y', $data['start_date']);

        $validator = Validator::make($data, Injection::rules());
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors());
        }


        $production = Production::firstOrCreate(['date' => $date, 'location_id' => $data['location_id']]);
        $production->update(['avg_gas' => $request->avg_gas, 'avg_oil' => $request->avg_oil,
            'avg_water' => $request->avg_water]);
        unset($data['avg_oil']);
        unset($data['avg_gas']);
        unset($data['avg_water']);
        // LEAVE ON INJECTION -- unset($data['target_ppm']);

        unset($data['date']);
        unset($data['_token']);
        unset($data['id']);
        $data['start_date'] = $startDate;

        $injection = new Injection($data);
        $injection->date = $date;
        $injection->type = "CONTINUOUS";
        // if (!$data['usage_rate']) $injection->usage_rate = null;
        if (!$data['target_rate']) $injection->target_rate = null;
        $estimateInventory = null;
        if (strlen($data['estimate_usage_rate']) && strlen($data['chemical_start'])) {
            $estimateInventory = $injection->calculateEstimateInventory($data['estimate_usage_rate'], $data['chemical_start'], $data['chemical_delivered']);
        }
        $injection->estimate_inventory = $estimateInventory;

        $injection->unit_cost = $data['unit_cost'] * 100;
        $injection->save();
        return $saveState ? redirect($saveState) : redirect('injections/continuous?type=location&id='. $injection->location_id);
    }

    public function getEdit($id)
    {
        $injection = Injection::find($id);
        $production = $injection->getProduction();
        $location_id = $injection->location_id;
        $button = "Update Injection";

        if ($injection->status === Injection::STATUS_READ_ONLY) {
            return redirect()->back();
        }

        if ($injection->type === Injection::TYPE_BATCH)
        {
            $action = "InjectionController@postStandardBatchUpdate";
            return view('injections.batch.form', compact('injection', 'production', 'action', 'button', 'location_id'));
        }
        elseif ($injection->type === Injection::TYPE_CONTINUOUS)
        {
            $action = "InjectionController@postStandardContinuousUpdate";
            return view('injections.continuous.form', compact('injection', 'production', 'action', 'button', 'location_id'));
        }
    }

    public function postBatchData(Request $request)
    {
        $batches = $this->_batches($request);

        $batches->selectRaw('injections.id as DT_RowId, injections.name as chemical, injections.comments as comments,
            DATE_FORMAT(injections.date,\'%Y-%m\') as injection_date, locations.name as location, locations.description as location_desc, injections.batch_size,
            injections.circulation_time, injections.diluent_required, injections.scheduled_batches, injections.status as status,
            (injections.unit_cost * 0.01) as unit_cost, injections.target_frequency, production.avg_gas as avg_gas,
            production.avg_oil as avg_oil, production.avg_water as avg_water, locations.cost_centre, injections.chemical_type,
            (production.avg_gas + production.avg_oil + production.avg_water) as total_production,
            (injections.batch_size * injections.scheduled_batches * injections.unit_cost * 0.01) as batch_cost,
            (injections.target_frequency * injections.batch_size * injections.unit_cost * 0.01) as target_cost,
            ((injections.batch_size * injections.unit_cost * 0.01) * (injections.scheduled_batches - injections.target_frequency)) as over_injection,
            IF((injections.batch_size * injections.unit_cost * 0.01) < (injections.target_frequency * injections.batch_size * injections.unit_cost * 0.01),1,0) as under_injection,
            IF(injections.chemical_type = "corrosion_inhibitor", (injections.batch_size * injections.scheduled_batches / production.avg_water) / 30, "")as inhibitor_ration,
            IF(injections.chemical_type = "demulsifier", (injections.batch_size * injections.scheduled_batches / production.avg_oil) / 30 , "") as paraffin_ratio')
            ->groupBy(DB::raw('injections.id'));



        $data = Datatables::of($batches);

        $data->addColumn('location', function($batch) {
            $injection = Injection::find($batch->DT_RowId);
            if ($injection->status == Injection::STATUS_READ_ONLY) {
                return '<td class="editable sorting"><span data-status="1" id="js-injection-status"></span>' . $batch->location .'</td>';
            }

            return '<td class="editable sorting">' . $batch->location .'</td>';
        });


        return $data->addColumn('action', function($batch){
            $delete = \Auth::user()->admin ? '<a href="/injections/delete/'. $batch->DT_RowId.'" onclick="javascript:if(window.confirm(\'If you delete Injection then all the locations and data associated with it will be deleted as well. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
            $edit = ($batch->status !== Injection::STATUS_READ_ONLY) ? '<a href="/injections/edit/'. $batch->DT_RowId.'"><i class="fa fa-edit fa-2x"></i></a>' : '';
            return '<td class="action">'. $edit.$delete.'</td>';
        })
        ->rawColumns(['location','action'])
        ->make(true);
    }

    public function postContinuousData(Request $request)
    {
        $batches = $this->_batches($request);

        $batches ->selectRaw('injections.id as DT_RowId, injections.name as name, injections.comments as comments,
            DATE_FORMAT(injections.date,\'%Y-%m\') as date, DATE_FORMAT(injections.start_date,\'%d-%m-%Y\') as start_date, locations.name as location, injections.days_in_month as days_in_month,
            production.avg_gas as avg_gas, locations.cost_centre, injections.status as status, injections.tank_capacity as tank_capacity,
            production.avg_oil as avg_oil, production.avg_water as avg_water, locations.description as location_desc,
            (production.avg_gas + production.avg_oil + production.avg_water) as total_production,
            injections.based_on as based_on, injections.chemical_start as chemical_start,
            injections.chemical_delivered as chemical_delivered, injections.chemical_end as chemical_end,
            injections.target_ppm as target_ppm, injections.chemical_type as chemical_type,
            (COALESCE(injections.chemical_start, 0) + COALESCE(injections.chemical_delivered, 0) - COALESCE(injections.chemical_end, 0)) as chemical_used,
            ((injections.chemical_start + injections.chemical_delivered - injections.chemical_end) / injections.days_in_month) as usage_rate,

            (chemical_end / ((injections.chemical_start + injections.chemical_delivered - injections.chemical_end) / injections.days_in_month)) as days_remaining,

            IF((((injections.chemical_start + injections.chemical_delivered - injections.chemical_end) / injections.days_in_month) -
            injections.vendor_target) > 0,
            (((injections.chemical_start + injections.chemical_delivered - injections.chemical_end) / injections.days_in_month) -
            injections.vendor_target),
            0) as over_under,

            COALESCE(injections.target_rate,
            CASE based_on
            WHEN "water" THEN (injections.target_ppm * production.avg_water) / 1000
            WHEN "oil" THEN (injections.target_ppm * production.avg_oil) / 1000
            WHEN "gas" THEN (injections.target_ppm * production.avg_gas) / 1000
            WHEN "oil_and_water" THEN (injections.target_ppm * (production.avg_oil + production.avg_water)) / 1000
            WHEN "all" THEN (injections.target_ppm * (production.avg_gas + production.avg_oil + production.avg_water)) / 1000
            ELSE "NIL"
            END) as target_rate,
            injections.chemical_inventory as inventory,
            injections.vendor_target as vendor_target, injections.min_rate as min_rate,
            (injections.unit_cost * 0.01) as unit_cost,

            COALESCE(injections.actual_ppm,
            ((injections.chemical_start + injections.chemical_delivered - injections.chemical_end) / injections.days_in_month) * 1000 /
            CASE based_on
            WHEN "water" THEN production.avg_water
            WHEN "oil" THEN production.avg_oil
            WHEN "gas" THEN production.avg_gas
            WHEN "oil_and_water" THEN production.avg_oil + production.avg_water
            WHEN "all" THEN production.avg_gas + production.avg_oil + production.avg_water
            ELSE "NIL"
            END) as actual_ppm,

            IF((((injections.chemical_start + injections.chemical_delivered - injections.chemical_end) / injections.days_in_month) -
            injections.vendor_target) > 0,
            (((injections.chemical_start + injections.chemical_delivered - injections.chemical_end) / injections.days_in_month) -
            injections.vendor_target) * (injections.unit_cost * 0.01) * injections.days_in_month,
            0 ) as over_cost,

            (injections.vendor_target * injections.unit_cost * 0.01 * injections.days_in_month) as vendor_budget,
            (COALESCE(injections.target_rate,
            CASE based_on
            WHEN "water" THEN (injections.target_ppm * production.avg_water) / 1000
            WHEN "oil" THEN (injections.target_ppm * production.avg_oil) / 1000
            WHEN "gas" THEN (injections.target_ppm * production.avg_gas) / 1000
            WHEN "oil_and_water" THEN (injections.target_ppm * (production.avg_oil + production.avg_water)) / 1000
            WHEN "all" THEN (injections.target_ppm * (production.avg_gas + production.avg_oil + production.avg_water)) / 1000
            ELSE "NIL"
            END) * injections.days_in_month * injections.unit_cost * 0.01) as target_budget,
            (((COALESCE(injections.chemical_start, 0) + COALESCE(injections.chemical_delivered, 0) - COALESCE(injections.chemical_end, 0)) / COALESCE(injections.days_in_month,30)) * injections.unit_cost * 0.01 * COALESCE(injections.days_in_month,30)) as total_monthly_cost')
            ->groupBy(DB::raw('injections.id'));

        $data = Datatables::of($batches);
        $data->addColumn('estimate_usage_rate', function($batch) {
            $injection = Injection::find($batch->DT_RowId);
            return $injection->estimateUsageRate();
        });

        $data->addColumn('estimate_inventory', function($batch) {
            $injection = Injection::find($batch->DT_RowId);
            return $injection->calculateEstimateInventory($injection->estimateUsageRate(), $injection->chemical_start, $injection->chemical_delivered);
        });


        // $data->addColumn('uwi', function($batch){
        //     $injection = Injection::find($batch->DT_RowId);
        //     if ($batch->status == Injection::STATUS_READ_ONLY) {
        //             return '<td class="editable sorting"><span data-status="1" id="js-injection-status">' . $injection->uwi .'</td>';
        //     }
        // return '<td class="editable sorting">' . $injection->uwi .'</td>';
        // });

        // $data->addColumn('days_remaining', function($batch) {
        //     $injection = Injection::find($batch->DT_RowId);
        //     $estimateUsageRate = $injection->estimateUsageRate();
        //     $estimateInventory = $injection->calculateEstimateInventory($estimateUsageRate, $injection->chemical_start, $injection->chemical_delivered);


        //     if ($estimateUsageRate <= 0 || $estimateInventory <= 0) {
        //         return 0;
        //     }

        //     return round(($estimateInventory/$estimateUsageRate));
        // });

        return $data->addColumn('action', function($batch){
            $delete = \Auth::user()->admin ? '<a href="/injections/delete/'. $batch->DT_RowId.'" onclick="javascript:if(window.confirm(\'If you delete Injection then all the locations and data associated with it will be deleted as well. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
            $edit = ($batch->status !== Injection::STATUS_READ_ONLY) ? '<a href="/injections/edit/'. $batch->DT_RowId.'"><i class="fa fa-edit fa-2x"></i></a>' : '';
            return '<td class="action">'. $edit.$delete.'</td>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function postContinuousUpdate(Request $request)
    {
        foreach($request->data as $key => $data)
        {
            $returnData = new \stdClass();
            $injection = Injection::find($key);
            $injection->guaranteeProduction();

            foreach($data as $attribute => $value )
            {
                $returnData->DT_RowId = (string)$key;

                if (in_array($attribute, ['date', 'name', 'days_in_month', 'based_on', 'estimate_usage_rate', 'comments',
                    'chemical_start', 'chemical_delivered', 'chemical_type', 'chemical_end', 'usage_rate', 'target_rate',
                    'vendor_target', 'min_rate', 'unit_cost', 'avg_gas', 'avg_oil', 'avg_water', 'target_ppm']))
                {
                    // if (isset($data['usage_rate']) && (int)$data['usage_rate'] == 0) $data['usage_rate'] = null;
                    if (isset($data['target_rate']) && (int)$data['target_rate'] == 0) $data['target_rate'] = null;
                    if (isset($data['date'])) $data['date'] = $data['date'].'-01';
                    if (isset($data['unit_cost'])) $data['unit_cost'] = $data['unit_cost'] * 100;

                    $production = $injection->getProduction();

                    \Log::alert('Production ID: '.$production->id);
                    if (! $production->id)
                    {
                        $production->location_id = $injection->location_id;
                        // $production->date = $injection->date->format('Y-m').'-01';
                        $production->save();
                        \Log::alert('Updated ID: '.$production->id);
                    }

                    if (isset($data['avg_oil']) || isset($data['avg_gas']) || isset($data['avg_water']))
                        $production->update($data);
                    else
                        $injection->update($data);
                }
            }
            // $returnData->DT_RowId = $injection->id;
            // $returnData->location = $injection->location->name;
            // $returnData->date = $injection->date;

            $data = [];
            $data[] = $returnData;
            $another = new \stdClass;
            $another->data = $data;
        }

        return json_encode($another);
    }

    public function postBatchUpdate(Request $request)
    {

        foreach($request->data as $key => $data)
        {
            $returnData = new \stdClass();
            $injection = Injection::find($key);
            $injection->guaranteeProduction();

            foreach($data as $attribute => $value )
            {
                $returnData->DT_RowId = (string)$key;

                if (in_array($attribute, ['injection_date', 'chemical', 'comments',
                    'batch_size', 'target_frequency', 'circulation_time', 'diluent_required', 'chemical_type',
                    'scheduled_batches', 'unit_cost', 'avg_gas', 'avg_oil', 'avg_water','target_ppm']))
                {
                    if (isset($data['injection_date'])) $data['date'] = $data['injection_date'].'-01';
                    if (isset($data['chemical'])) $data['name'] = $data['chemical'];
                    if (isset($data['unit_cost'])) $data['unit_cost'] = $data['unit_cost'] * 100;
                    unset($data['injection_date']);
                    unset($data['chemical']);

                    $production = $injection->getProduction();

                    if (isset($data['target_ppm']) || isset($data['avg_oil']) || isset($data['avg_gas']) || isset($data['avg_water'])) {
                        if ($production->exists) {
                            $production->update($data);
                        } else {
                            $production = Production::firstOrCreate(['date' => $injection->date, 'location_id' => $injection->location_id]);
                            $production->update($data);
                        }
                    } else {
                        $injection->update($data);
                    }
                }
            }
            // $returnData->DT_RowId = $injection->id;
            // $returnData->location = $injection->location->name;
            // $returnData->date = $injection->date;
            // $returnData->water_production = 100;

            $data = [];
            $data[] = $returnData;
            $another = new \stdClass;
            $another->data = $data;
        }

        return json_encode($another);
    }

    protected function _getTitle($id, $type)
    {
        if ($type === "Field")
            return Field::find($id)->name;
        elseif ($type === "Area")
            return Area::find($id)->name;
        elseif ($type === "Location")
            return Location::find($id)->name;
    }

    protected function _batches($request)
    {
        $batches = DB::table('companies')
            ->leftJoin('areas', DB::raw('areas.company_id'), '=', DB::raw('companies.id'));

        if ($request->area_id)
            $batches = $batches->leftJoin('fields', DB::raw('fields.area_id'), '=', DB::raw($request->area_id))
                ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw('fields.id'));
        elseif ($request->field_id)
            $batches = $batches->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id));
        elseif ($request->location_id)
            $batches = $batches->leftJoin('locations', DB::raw('locations.id'), '=', DB::raw($request->location_id));
        else
            $batches = $batches->leftJoin('fields', DB::raw('fields.area_id'),'=', DB::raw('areas.id'))
                ->leftJoin('locations', DB::raw('locations.area_id'), '=', DB::raw('fields.id'));
        // if ($request->field_id != 'ALL')
        //     $batches = $batches->leftJoin('locations', 'locations.field_id', '=', DB::raw($request->field_id));
        // else
        //     $batches = $batches->leftJoin('locations', 'locations.field_id', '=', 'fields.id');


        $batches = $batches->leftJoin('injections', DB::raw('injections.location_id'), '=', DB::raw('locations.id'))
            ->leftJoin('production', function($join)
            {
                $join->on(DB::raw('production.location_id'), '=', DB::raw('injections.location_id'));
                $join->on(DB::raw("DATE_FORMAT(production.date, '%Y-%m')"), '=', DB::raw("DATE_FORMAT(injections.date, '%Y-%m')"));
            })
            ->where(DB::raw('companies.id'), Auth::user()->activeCompany()->id)
            ->where(DB::raw('injections.type'), $request->type);
        return $batches;
    }

    public function postStandardBatchUpdate(Request $request)
    {
        $saveState = Session::get('saveState', null);
        $injection = Injection::find($request->id);
        $injection->guaranteeProduction();

        $data = $request->all();
        unset($data['_token']);
        unset($data['id']);
        $data['date'] = $data['date'].'-01';
        $data['unit_cost'] = $data['unit_cost'] * 100;
        $production = $injection->getProduction();
        if ($production->exists) {
            $production->update(['avg_oil' => $request->avg_oil,
                'avg_gas' => $request->avg_gas, 'avg_water' => $request->avg_water]);
        } else {
            $production = Production::firstOrCreate(['date' => $data['date'], 'location_id' => $data['location_id']]);
            $production->update(['avg_gas' => $request->avg_gas, 'avg_oil' => $request->avg_oil,
                'avg_water' => $request->avg_water]);
        }

        unset($data['avg_oil']);
        unset($data['avg_gas']);
        unset($data['avg_water']);
        $injection->update($data);

        return $saveState ? redirect($saveState) : redirect('injections/batch?type=location&id='. $injection->location_id);
    }

    public function postStandardContinuousUpdate(Request $request)
    {
        $saveState = Session::get('saveState', null);
        $injection = Injection::find($request->id);
        $injection->guaranteeProduction();

        $data = $request->all();
        $validator = Validator::make($data, Injection::rules(), Injection::errorMessages());
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors());
        }
        // if ($data['usage_rate'] == 0) $data['usage_rate'] = null;
        if ($data['target_rate'] == 0) $data['target_rate'] = null;
        $data['unit_cost'] = $data['unit_cost'] * 100;
        $data['date'] = $data['date'].'-01';
        $data['start_date'] = date('Y-m-d', strtotime($data['start_date']));
        $production = $injection->getProduction();
        $production->update(['avg_oil' => $request->avg_oil,
            'avg_gas' => $request->avg_gas, 'avg_water' => $request->avg_water]);
        // unset($data['target_ppm']);
        unset($data['avg_oil']);
        unset($data['avg_gas']);
        unset($data['avg_water']);

        $estimateInventory = null;
        if (strlen($data['estimate_usage_rate']) && strlen($data['chemical_start'])) {
            $estimateInventory = $injection->calculateEstimateInventory($data['estimate_usage_rate'], $data['chemical_start'], $data['chemical_delivered']);
        }

        $data['estimate_inventory'] = $estimateInventory;

        $injection->update($data);

        return $saveState ? redirect($saveState) : redirect('injections/continuous?type=location&id='. $injection->location_id);
    }

    public function getDelete($id)
    {
        $item = Injection::find($id);
        $location = $item->location_id;
        $item->delete();
        return redirect()->back(); // redirect('injections/continuous?type=location&id='. $location)->withMessage('Injection Deleted.');
    }
}