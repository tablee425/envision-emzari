<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Arrow\Http\Requests\PiggingRequest;
use DB;
use BrowserDetect;
use Datatables;
use Arrow\Pigging;
use Arrow\Location;
use Session;

class PiggingController extends Controller
{
    public function getIndex(Request $request)
    {
        Session::put('saveState', $request->fullUrl());
        $type = $request->type ?: '';
        $id = $request->id ?: 0;
        if(BrowserDetect::isMobile())
            return view('piggings.mobile', compact('type', 'id'));
        return view('piggings.index', compact('type', 'id'));
    }

    public function getCreate(Request $request)
    {
        $pigging = new Pigging;
        $button = "Create Pigging";
        $location = Location::find($request->location_id);
        $end_locations = $location->field->locations;
        if (!$location) return redirect()->back();
        
        $action = "PiggingController@postCreate";
        return view('piggings.form', compact('pigging', 'action', 'button', 'location', 'end_locations'));
    }

    public function postCreate(PiggingRequest $request)
    {
        $saveState = Session::get('saveState', null);
        $data = $request->all();
        $this->__nullDates($data);
        Pigging::create($data);
        return $saveState ? redirect($saveState) : redirect('piggings?type=location&id='. $request->start_location_id);
    }

    public function getEdit($id)
    {
        $pigging = Pigging::find($id);
        // return dd($pigging);
        $button = "Update Pigging";
        $location = $pigging->startLocation;
        $end_locations = $location->field->locations;
        if (!$location) return redirect()->back();
        
        $action = "PiggingController@postUpdate";
        return view('piggings.form', compact('pigging', 'action', 'button', 'location', 'end_locations'));
    }

    public function postUpdate(PiggingRequest $request)
    {
        $saveState = Session::get('saveState', null);
        $data = $request->all();
        $pigging = Pigging::find($request->id);
        $this->__nullDates($data);
        $pigging->update($data);
        return $saveState ? redirect($saveState) : redirect('piggings?type=location&id='. $request->start_location_id);
    }

    public function postTableUpdate(Request $request)
    {
        foreach($request->data as $key => $data)
        {
            $pigging = Pigging::find($key);
            $returnData = new \stdClass();
            foreach($data as $attribute => $value )
            {
                $returnData->DT_RowId = (string)$key;

                $pigging->update([$attribute => $value]);
            }
            $data = [];
            $data[] = $returnData;
            $another = new \stdClass;
            $another->data = $data;
        }

        return json_encode($another);
    }

    public function postData(Request $request)
    {
        $piggings = DB::table('areas');

        if ($request->type == 'area')
            $piggings = $piggings->leftJoin('fields', DB::raw('fields.area_id'), '=', DB::raw($request->id))
                                   ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw('fields.id'));
        elseif ($request->type == 'field')
            $piggings = $piggings->leftJoin('fields', DB::raw('fields.area_id'), '=', DB::raw('areas.id'))
                                   ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->id));
        elseif ($request->type == 'location')
            $piggings = $piggings->leftJoin('fields', DB::raw('fields.area_id'),'=', DB::raw('areas.id'))
                                   ->leftJoin('locations', DB::raw('locations.id'), '=', DB::raw($request->id));

        $piggings = $piggings->join('piggings', DB::raw('piggings.start_location_id'), '=', DB::raw('locations.id'))
            ->leftJoin('locations as end', DB::raw('end.id'), '=', DB::raw('piggings.end_location_id'))
            ->select([DB::raw('piggings.id as DT_RowId'), DB::raw('locations.name as start_location'), DB::raw('end.name as end_location'),
                DB::raw('piggings.od'), DB::raw('piggings.thickness'), DB::raw('piggings.license'), DB::raw('piggings.length'), DB::raw('piggings.frequency'),
                DB::raw('piggings.scheduled_on'), DB::raw('piggings.shipped_on'), DB::raw('piggings.pulled_on'),
                DB::raw('piggings.line_type'), DB::raw('piggings.line_pressure'), DB::raw('piggings.pressure_switch'), DB::raw('piggings.MOP'),
                DB::raw('piggings.cancelled_on'), DB::raw('piggings.pig_size'), DB::raw('piggings.pig_number'), DB::raw('piggings.gauged'), DB::raw('piggings.condition'), DB::raw('piggings.wax'), DB::raw('piggings.order'),
                DB::raw('piggings.corr_inh_vol'), DB::raw('piggings.biocide_vol'), DB::raw('piggings.diluent'), DB::raw('piggings.water_vol'),
                DB::raw('piggings.field_operator'), DB::raw('piggings.comments')])
                
            ->whereNotNull(DB::raw('locations.name'))
            ->whereNotNull(DB::raw('locations.id'))
            ->groupBy(DB::raw('piggings.id'));

        return Datatables::of($piggings)
            ->addColumn('action', function($pigging){
                $delete = \Auth::user()->admin ? '<a href="/piggings/delete/'. $pigging->DT_RowId.'" onclick="javascript:if(window.confirm(\'You are about to delete this pigging. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
                $edit = '<a href="/piggings/edit/'. $pigging->DT_RowId .'"><i class="fa fa-edit fa-2x"></i></a>';
                return '<td class="action">'. $edit.$delete.'</td>';
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function getDelete($id)
    {
        $item = Pigging::find($id);
        $location = $item->location_id;
        $item->delete();
        return redirect('piggings?type=location&id='. $location)->withMessage('Pigging Deleted.');
    }

    private function __nullDates(array &$dates)
    {
        $check = ['scheduled_on', 'pulled_on', 'cancelled_on', 'shipped_on'];
        foreach($check as $date)
        {
            $dates[$date] = $dates[$date] ? $dates[$date] : null;
        }
    }
}
