<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Arrow\Analysis;
use Auth;
use DB;
use Datatables;

class AnalysisController extends Controller
{
    public function getIndex(Request $request)
    {
    	$type = $request->type ?: 'area';
    	$id = $request->id ?: 1;
        $location_id = ($type == "location") ? $id : null;
    	return view('analysis.index', compact('type', 'id', 'location_id'));
    }

    public function getCreate(Request $request, $location_id = null)
    {
        $request->session()->flash('redirect_url', $request->headers->get('referer'));
        $action = 'AnalysisController@postStore';
        $analysis = new Analysis;
        $button = "Create Analysis";

        $locations = auth()->user()->activeCompany()->locations();
        return view('analysis.form', compact('action', 'locations', 'location_id', 'analysis', 'button'));
    }
    public function postUpdate(Request $request)
    {
        $analysis = Analysis::find($request->id);
        $analysis->update(['date' => $request->date.'-01', 
                          'corrosion_residuals' => $request->corrosion_residuals,
                          'scale_residuals' => $request->scale_residuals, 
                          'water_qualities' => $request->water_qualities,
                          'comments' => $request->comments]);
        return redirect($request->session()->get('redirect_url'));
    }

    public function postStore(Request $request)
    {
        $analysis = Analysis::create(['location_id' => $request->location_id,
                                      'date' => $request->date.'-01', 
                                      'corrosion_residuals' => $request->corrosion_residuals,
                                      'scale_residuals' => $request->scale_residuals, 
                                      'water_qualities' => $request->water_qualities,
                                      'comments' => $request->comments]);
        
        return redirect($request->session()->get('redirect_url'));
    }

    public function getEdit(Request $request, $id)
    {
        $request->session()->flash('redirect_url', $request->headers->get('referer'));
        $action = 'AnalysisController@postUpdate';
        $analysis = Analysis::find($id);
        $button = "Update Analysis";
        $location_id = $analysis->location_id;
        $locations = auth()->user()->activeCompany()->locations();
        return view('analysis.form', compact('action', 'locations', 'location_id', 'analysis', 'button'));
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
        $records = $records->join('analysis', DB::raw('analysis.location_id'), '=', DB::raw('locations.id'))
            ->where(DB::raw('companies.id'), Auth::user()->activeCompany()->id)
         ->selectRaw('analysis.id as DT_RowId, locations.id as location_id, locations.name as location, locations.description as location_desc,
         	DATE_FORMAT(analysis.date, \'%Y-%m\') as date, analysis.corrosion_residuals, analysis.scale_residuals, analysis.water_qualities,
         	analysis.comments')
         ->groupBy(DB::raw('analysis.id'));
        $data = Datatables::of($records);
        $data->addColumn('files', function($analysis) {
                return '<a class="btn btn-primary" href="/files?type=location&id='.$analysis->location_id.'">Attachments</a>';
            }
        );
        $data->addColumn('action', function($analysis) {
                return '<a class="btn btn-info" href="/analysis/edit/'.$analysis->DT_RowId.'">View/Edit</a>';
            }
        );
        return $data->make(true);
    }
}
