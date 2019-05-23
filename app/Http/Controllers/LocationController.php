<?php

namespace Arrow\Http\Controllers;

use DB;
use Session;
use Datatables;
use BrowserDetect;
use Illuminate\Http\Request;

use Arrow\Location;
use Arrow\Field;
use Arrow\Area;
use Arrow\Chemical;
use Arrow\Injection;
use Arrow\Http\Requests;
use Arrow\Http\Requests\LocationRequest;
use Carbon\Carbon;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->checkAuth($request);
            return $next($request);
        });
    }

    private function checkAuth($request) {
        if ($request->field_id)
        {
            parent::isAuthorized($request->field_id, 'fields');
        }
        elseif ($request->area_id)
        {
            parent::isAuthorized($request->area_id, 'areas');
        }
    }

    public function getIndex(Request $request)
    {
        $data =[];
        if ($request->field_id)
        {
            $data['id'] = $request->field_id;
            $data['type'] = 'field';
            $data['name'] = Field::find($request->field_id)->name;
        }
        elseif ($request->area_id)
        {
            $data['id'] = $request->area_id;
            $data['type'] = 'area';
            $data['name'] = Area::find($request->area_id)->name;
        }
        $data['search_term'] = isset($request->search_term) ? $request->search_term : null;
        // Take field id, display locations on datatable
        if(BrowserDetect::isMobile())
            return view('locations.mobile', $data);
        return view('locations.index', $data);
    }

    public function getCreate(Request $request)
    {
        $location = new Location;
        $button = "Create Location";
        $action = "LocationController@postStore";
        $field = Field::find($request->field_id);
        $companyFields = auth()->user()->activeCompany()->fields;
        return view('locations.form', compact('location', 'field', 'button', 'action', 'companyFields'));
    }

    public function getEdit($id)
    {
        $location = Location::find($id);
        parent::isAuthorized($location->field_id, 'fields');
        $button = "Update Location";
        $action = "LocationController@putStore";
        $companyFields = auth()->user()->activeCompany()->fields;
        $chemicals = Chemical::where('location_id', $location->id)->get();
        return view('locations.form', compact('location', 'button', 'action', 'chemicals', 'companyFields'));
    }

    public function putStore(Request $request)
    {
        $location = Location::find($request->location_id);
        $location->name = $request->name;
        $location->field_id = $request->field_id;
        $location->formation = $request->formation;
        $location->description = $request->description;
        $location->unit_of_measure = $request->unit_of_measure;
        $location->cost_centre = $request->cost_centre;
        $location->save();

        // Update Chemicals
        Chemical::where('location_id', $location->id)->delete();
        if (isset($request->chemicals))
        {
            foreach($request->chemicals as $index => $chemical)
            {
                if ($chemical)
                {
                    Chemical::create(['location_id' => $location->id, 'name' => $chemical,
                                      'type' => $request->types[$index],
                                      'chemical_type' => $request->chemical_types[$index]]);
                }
            }
        }

        return redirect('/locations?field_id='. $request->field_id)->withMessage('Location Updated.');
    }

    public function postStore(LocationRequest $request)
    {
        $location = Location::create(['name' => $request->name, 'unit_of_measure' => $request->unit_of_measure,
                                      'formation' => $request->formation, 'field_id' => $request->field_id,
                                      'description' => $request->description, 'cost_centre' => $request->cost_centre]);

        if (isset($request->chemicals))
        {
            foreach($request->chemicals as $index => $chemical)
            {
                if ($chemical)
                {
                    Chemical::create(['location_id' => $location->id, 'name' => $chemical,
                                      'type' => $request->types[$index],
                                      'chemical_type' => $request->chemical_types[$index]]);
                }

            }
        }

        return redirect('/locations?field_id='. $request->field_id);
    }

    public function postData(Request $request)
    {
        $locations = DB::table('areas');

        if ($request->area_id)
            $locations = $locations->leftJoin('fields', DB::raw('fields.area_id'), '=', DB::raw($request->area_id))
                                   ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw('fields.id'));
        elseif ($request->field_id)
            $locations = $locations->leftJoin('fields', DB::raw('fields.area_id'), '=', DB::raw('areas.id'))
                                   ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id));
        else
            $locations = $locations->leftJoin('fields', DB::raw('fields.area_id'),'=', DB::raw('areas.id'))
                                   ->leftJoin('locations', DB::raw('locations.area_id'), '=', DB::raw('fields.id'));

        $locations = $locations->leftJoin('injections', DB::raw('injections.location_id'), '=', DB::raw('locations.id'))
            ->select([DB::raw('locations.id as DT_RowId'), DB::raw('locations.name'),
                DB::raw('count(distinct injections.id) as injection_count')])
            ->whereNotNull(DB::raw('locations.name'))
            ->groupBy(DB::raw('locations.id'));

        return Datatables::of($locations)
            ->addColumn('piggings', function($location){
                return '<td class="action"><a href="/piggings?type=location&id='. $location->DT_RowId .'" class="btn btn-info">View Pig Runs</a></td>';
            })
            ->addColumn('continuous', function($location){
                return '<td class="action"><a href="/injections/continuous?type=location&id='. $location->DT_RowId .'" class="btn btn-success">View Continuous</a></td>';
            })
            ->addColumn('batch', function($location){
                return '<td class="action"><a href="/injections/batch?type=location&id='. $location->DT_RowId .'" class="btn btn-warning">View Batch</a></td>';
            })
            ->addColumn('composition', function($location) {
                return '<td class="action"><a href="/composition?type=location&id='. $location->DT_RowId .'" class="btn btn-info">Composition</a></td>';
            })
            ->addColumn('analysis', function($location) {
                return '<td class="action"><a href="/analysis?type=location&id='. $location->DT_RowId .'" class="btn btn-success">Analysis</a></td>';
            })
            ->addColumn('files', function($location) {
                return '<td class="action"><a href="/files?type=location&id='. $location->DT_RowId .'" class="btn btn-warning">Files</a></td>';
            })
            ->addColumn('action', function($location){
                $delete = \Auth::user()->admin ? '<a href="/locations/delete/'. $location->DT_RowId.'" onclick="javascript:if(window.confirm(\'If you delete Location then data associated with it will be deleted as well. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
                return '<td class="action"><a href="/locations/edit/'. $location->DT_RowId.'"><i class="fa fa-edit fa-2x"></i></a> '.$delete.'</td>';
            })
            ->rawColumns(['piggings','continuous','batch','composition','analysis','files','action'])
            ->make(true);
    }

    public function getDelete($id)
    {
        $location = Location::find($id);
        $field = $location->field_id;
        $location->delete();
        return redirect('/locations?field_id='. $field)->withMessage('Location Deleted.');
    }

    public function listChemicals(Location $location)
    {
        $chemicals = $location->injections()->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), Carbon::now()->format('Y-m'))->get();
        if($chemicals->isEmpty())
        {
            return $location->injections()->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), Carbon::now()->subMonth()->format('Y-m'))->get();
        }
        return $chemicals;
    }
}
