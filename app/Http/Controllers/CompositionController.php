<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Auth;
use DB;
use Datatables;
use Arrow\Composition;

class CompositionController extends Controller
{
    public function getIndex(Request $request)
    {
    	$type = $request->type ?: 'area';
    	$id = $request->id ?: 1;
        $location_id = ($type == "location") ? $id : null;
    	return view('composition.index', compact('type', 'id', 'location_id'));
    }
    public function getCreate(Request $request, $location_id = null)
    {
        $request->session()->flash('redirect_url', $request->headers->get('referer'));
        $action = 'CompositionController@postStore';
        $composition = new Composition;
        $button = "Create Composition";
        $locations = auth()->user()->activeCompany()->locations();
        return view('composition.form', compact('action', 'location_id', 'locations', 'composition', 'button'));
    }
    public function postUpdate(Request $request)
    {
        $composition = Composition::find($request->id);
        $composition->update(['date' => $request->date.'-01', 
                          'iron' => $request->iron,
                          'manganese' => $request->manganese, 
                          'chloride' => $request->chloride,
                          'comments' => $request->comments]);
        return redirect($request->session()->get('redirect_url'));
    }

    public function postStore(Request $request)
    {
        $composition = Composition::create(['location_id' => $request->location_id,
                                      'date' => $request->date.'-01', 
                                      'iron' => $request->iron,
                                      'manganese' => $request->manganese, 
                                      'chloride' => $request->chloride,
                                      'comments' => $request->comments]);
        return redirect($request->session()->get('redirect_url'));
    }

    public function getEdit(Request $request, $id)
    {
        $request->session()->flash('redirect_url', $request->headers->get('referer'));
        $action = 'CompositionController@postUpdate';
        $composition = Composition::find($id);
        $button = "Update Composition";
        $location_id = $composition->location_id;
        $locations = auth()->user()->activeCompany()->locations();
        return view('composition.form', compact('action', 'location_id', 'locations', 'composition', 'button'));
    }
    public function postData(Request $request)
    {
    	$records = DB::table('companies')
            ->leftJoin('areas', DB::raw('areas.company_id'), '=', DB::raw('companies.id'));

        if ($request->area_id)
            $records = $records->leftJoin('fields', DB::raw('fields.area_id'), '=', DB::raw($request->area_id))
                ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw('fields.id'));
        elseif ($request->field_id)
            $records = $records->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id));
        elseif ($request->location_id)
            $records = $records->leftJoin('locations', DB::raw('locations.id'), '=', DB::raw($request->location_id));
        else
            $records = $records->leftJoin('fields', DB::raw('fields.area_id'),'=', DB::raw('areas.id'))
                ->leftJoin('locations', DB::raw('locations.area_id'), '=', DB::raw('fields.id'));
        $records = $records->join('composition', DB::raw('composition.location_id'), '=', DB::raw('locations.id'))
            ->where(DB::raw('companies.id'), Auth::user()->activeCompany()->id)
         ->selectRaw('composition.id as DT_RowId, locations.id as location_id, locations.name as location, locations.description as location_desc,
         	DATE_FORMAT(composition.date, \'%Y-%m\') as date, composition.iron, composition.manganese, composition.chloride,
         	composition.comments')
         ->groupBy(DB::raw('composition.id'));
        $data = Datatables::of($records);
        $data->addColumn('files', function($composition) {
                return '<a class="btn btn-primary" href="/files?type=location&id='.$composition->location_id.'">Attachments</a>';
            }
        );
        $data->addColumn('action', function($composition) {
                return '<a class="btn btn-info" href="/composition/edit/'.$composition->DT_RowId.'">View/Edit</a>';
            }
        );
        return $data->make(true);
    }
}
