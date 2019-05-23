<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests\PiggingRequest;
use Arrow\Area;
use Arrow\Field;
use Carbon\Carbon;
use Arrow\Pigging;
use DB;
use Datatables;

class PigRunController extends Controller
{
    public function index(Request $request)
    {
        $areas = auth()->user()->areas;
        $fields = null;
        $piggingArea = null;
        $piggingField = null;
        $piggingMonth = null;
        $piggingOperator = null;
        $piggingRunType = null;
        $month = null;
        $operator = null;
        if($request->area_id)
        {
            $piggingArea = Area::find($request->area_id);
            $fields = $piggingArea->fields;
            if($request->field_id)
            {
                $piggingField = Field::find($request->field_id);
                $month = Carbon::now()->month;

                // Populate Months Drop Down
                $months = [];
                $intervalDate = Carbon::now();
                $intervalDate->addMonth();
                $months[$intervalDate->month] = $intervalDate->format('F');
                for($m = 1; $m <= 4; $m++)
                {
                    $intervalDate->subMonth();
                    $months[$intervalDate->month] = $intervalDate->format('F');
                }

                $piggingMonth = $request->month;
                $piggingOperator = $request->operator;
                $runType = $request->run_type;

                return view('piggings.entry.tables', compact('piggingArea','piggingField','piggingMonth','piggingOperator','runType', 'months'));
                // switch($request->run_type) {
                //     case 'maintenance' : return view('piggings.entry.maintenance', compact('piggingArea','piggingField','piggingMonth','piggingOperator'));
                //     case 'corrosion' : return view('piggings.entry.corrosion', compact('piggingArea','piggingField','piggingMonth','piggingOperator'));
                //     case 'pressure' : return view('piggings.entry.pressure', compact('piggingArea','piggingField','piggingMonth','piggingOperator'));
                // }
            }
        } 

        return view('piggings.entry.index', compact('areas','fields','piggingArea','piggingField','piggingMonth','piggingOperator','piggingRunType','month'));
    }

    /**
     *  DataTables feed. 
     */
    public function postData(Request $request)
    {
        $year = $this->__getAppropriateYear($request->month);

        $piggings = DB::table('areas')
            ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id));

        $piggings = $piggings->join('piggings', DB::raw('piggings.start_location_id'), '=', DB::raw('locations.id'))
            ->leftJoin('locations as end', DB::raw('end.id'), '=', DB::raw('piggings.end_location_id'))
            ->select([DB::raw('piggings.id as DT_RowId'), DB::raw('locations.name as start_location'), DB::raw('end.name as end_location'),
                DB::raw('piggings.order'), DB::raw('piggings.od'), DB::raw('piggings.license'), DB::raw('piggings.frequency'),
                DB::raw('piggings.scheduled_on'), DB::raw('piggings.shipped_on'), DB::raw('piggings.pulled_on'),
                DB::raw('piggings.line_type'), DB::raw('piggings.line_pressure'), DB::raw('piggings.pressure_switch'), DB::raw('piggings.MOP'),
                DB::raw('piggings.cancelled_on'), DB::raw('piggings.pig_size'), DB::raw('piggings.pig_number'),
                DB::raw('piggings.corr_inh_vol'), DB::raw('piggings.biocide_vol'), DB::raw('piggings.water_vol'),
                DB::raw('piggings.field_operator'), DB::raw('piggings.comments')])   
            ->whereNotNull(DB::raw('locations.name'))
            ->whereNotNull(DB::raw('locations.id'))
            ->where(DB::raw('DATE_FORMAT(piggings.scheduled_on, "%Y-%c")'), $year."-".$request->month);
            
            if($request->operator)
            {
                $piggings = $piggings->where('piggings.field_operator', $request->operator);
            }

            if ($request->viewable == 'open')
            {
                $piggings == $piggings->whereNull(DB::raw('piggings.pulled_on'))
                                      ->whereNull(DB::raw('piggings.cancelled_on'))
                                      ->groupBy(DB::raw('piggings.id'));
            }
            elseif(!isset($request->viewable) || $request->viewable == 'all')
            {
                $piggings = $piggings->groupBy(DB::raw('piggings.id'));
            }

        return Datatables::of($piggings)
            ->addColumn('action', function($pigging) use($request) {
                $delete = \Auth::user()->admin ? '<a href="/piggings/delete/'. $pigging->DT_RowId.'" onclick="javascript:if(window.confirm(\'You are about to delete this pigging. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
                $edit = '<a href="/pig-runs/edit/'. $request->run_type. '/' . $pigging->DT_RowId .'?field_id='.$request->field_id.'&month='.$request->month.'&operator='.$request->operator.'&run_type='.$request->run_type.'"><i class="fa fa-edit fa-2x"></i></a>';
                return '<td class="action">'. $edit. '</td>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request, $run_type, Pigging $pigging)
    {
        $location = $pigging->startLocation;
        $field = Field::find($request->field_id);
        $piggingArea = $field->area_id;
        $piggingField = $field->id;
        $piggingMonth = $request->month;
        $piggingOperator = $request->operator;
        $runType = $request->run_type;

        $corrosion = $this->__corrosionValues($runType, $pigging);
 
        return view('piggings.entry.'. $request->run_type, compact('location', 'pigging', 'piggingArea', 'piggingField', 'piggingMonth', 'piggingOperator', 'runType', 'corrosion'));
    }

    public function update(PiggingRequest $request, Pigging $pigging)
    {
        $data = $request->all();
        $this->__nullDates($data);
        $pigging->update($data);
        $tables = $request->tables;
        // $piggingArea = $request->area_id;
        // $piggingField = $field->id;
        // $piggingMonth = $request->month;
        // $piggingOperator = $request->operator;
        // $runType = $request->run_type;
        return redirect('/pig-runs?area_id='.$tables['area_id'].'&field_id='.$tables['field_id'].'&month='.$tables['month'].'&operator='.$tables['operator'].'&run_type='.$tables['run_type']);
    }

    private function __getAppropriateYear($month)
    {
        $today = Carbon::now();
        if(($month == 1 || $month == 2 || $month ==3) && ($today->format('m') == 12 || $today->format('m') == 11 || $today->format('m') == 10))
        {
            return $today->addYear()->format('Y');
        }
        elseif(($month == 12 || $month == 11 || $month == 10) && ($today->format('m') == 1 || $today->format('m') == 2 || $today->format('m') ==3))
        {
            return $today->subYear()->format('Y');
        }
        else
        {
            return $today->format('Y');
        }
    }

    private function __nullDates(array &$dates)
    {
        $check = ['scheduled_on', 'pulled_on', 'cancelled_on', 'shipped_on'];
        foreach($check as $date)
        {
            if(isset($dates[$date]))
                $dates[$date] = $dates[$date] ? $dates[$date] : null;
        }
    }

    /**
     * Prepare array for finding past corrosion values if using the
     * batch corrosion view, otherwise return empty array.
     *
     * @param   string  $runType
     * @return  array
     */
    private function __corrosionValues($runType, $pigging)
    {
        $corrosion = [];
        if($runType == 'corrosion')
        {
            $priorPiggings = Pigging::where(['start_location_id' => $pigging->start_location_id,
                                             'end_location_id' => $pigging->end_location_id])
                                    ->orderBy('scheduled_on', 'desc')
                                    ->get();
                                    // ->keyBy(function($p) { return $p->scheduled_on->toDateString(); });
            // return dd($priorPiggings);
            $prior = $priorPiggings->first(function($key, $p) use($pigging) {
                return (($p->scheduled_on < $pigging->scheduled_on) && ($p->corr_inh_vol > 0 || $p->biocide_vol >0 || $p->diluent > 0));
            });
            // return dd($prior);
            if(!$prior) return [];

            $corrosion['corr_inh_vol'] = $prior->corr_inh_vol;
            $corrosion['biocide_vol'] = $prior->biocide_vol;
            $corrosion['diluent'] = $prior->diluent;   
        }
        return $corrosion;
    }
}
