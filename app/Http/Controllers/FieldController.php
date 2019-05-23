<?php

namespace Arrow\Http\Controllers;

use Arrow\Http\Requests;
use Illuminate\Http\Request;
use BrowserDetect;

use DB;
use Datatables;
use Auth;
use Arrow\Field;
use Arrow\Injection;

class FieldController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (isset($request->area_id)) {
                $this->isAuthorized($request->area_id, 'areas');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if(BrowserDetect::isMobile())
            return view('fields.mobile', ['area_id' => $request->area_id]);
        return view('fields.index', ['area_id' => $request->area_id]);
    }

    public function showCreateFieldForm(Request $request)
    {
        $field = new Field;
        $button = "Create Field";
        $action = "FieldController@store";
        $area_id = $request->area_id;
        $companyAreas = auth()->user()->activeCompany()->areas;
        return view('fields.form', compact('field', 'area_id', 'button', 'action', 'companyAreas'));
    }

    public function showEditFieldForm($id)
    {
        $field = Field::find($id);
        $this->isAuthorized($field->area_id, 'areas');
        $button = "Update Field";
        $action = "FieldController@update";
        $area_id = $field->area_id;
        $companyAreas = auth()->user()->activeCompany()->areas;
        return view('fields.form', compact('field', 'area_id', 'button', 'action', 'companyAreas'));
    }

    public function update(Request $request, $field)
    {
        $field->update($request->all());
        return redirect('fields?area_id='. $field->area_id);
    }

    public function store(Request $request)
    {
        $field = Field::create($request->all());
        return redirect('fields?area_id='. $field->area_id);
    }

    public function postData(Request $request)
    {
        $fields = DB::table('areas')
            ->where('areas.company_id', '=', Auth::user()->activeCompany()->id);

        if ($request->area_id)
            $fields = $fields->leftJoin('fields', 'fields.area_id', '=', DB::raw($request->area_id));
        else
            $fields = $fields->leftJoin('fields', 'fields.area_id', '=', 'areas.id');

        $fields = $fields->leftJoin('locations', 'locations.field_id', '=', 'fields.id')
            ->select(['fields.id as DT_RowId', 'fields.name', DB::raw('count(distinct locations.id) as location_count')])
            ->groupBy('fields.id');

        return Datatables::of($fields)
            ->addColumn('piggings', function($field){
                return '<td class="action"><a href="/piggings?type=field&id='. $field->DT_RowId .'" class="btn btn-info">View Pig Runs</a></td>';
            })
            ->addColumn('continuous', function($field){
                return '<td class="action"><a href="/injections/continuous?type=field&id='. $field->DT_RowId .'" class="btn btn-success">View Continuous</a></td>';
            })
            ->addColumn('batch', function($field){
                return '<td class="action"><a href="/injections/batch?type=field&id='. $field->DT_RowId .'" class="btn btn-warning">View Batch</a></td>';
            })
            ->addColumn('composition', function($field) {
                return '<td class="action"><a href="/composition?type=field&id='. $field->DT_RowId .'" class="btn btn-info">Composition</a></td>';
            })
            ->addColumn('analysis', function($field) {
                return '<td class="action"><a href="/analysis?type=field&id='. $field->DT_RowId .'" class="btn btn-success">Analysis</a></td>';
            })
            ->addColumn('files', function($field) {
                return '<td class="action"><a href="/files?type=field&id='. $field->DT_RowId .'" class="btn btn-warning">Files</a></td>';
            })
            ->addColumn('close', function($field){
                $field = Field::find($field->DT_RowId);
                $injections = $field->injections()->where(
                    \DB::raw("DATE_FORMAT(date, '%Y-%m')"),
                    date('Y-m', strtotime(date('Y-m')." -1 month")))
                    ->where('status', '=', Injection::STATUS_ENABLED)
                    ->get();

                if ($injections->count()) {
                    return '<td class="action"><a href="/closeout/'. $field->id .'" class="btn btn-info">Execute</a></td>';
                }
            })
            ->addColumn('action', function($field){
                $delete = \Auth::user()->admin ? '<a href="/fields/delete/'. $field->DT_RowId.'" onclick="javascript:if(window.confirm(\'If you delete Field then data associated with it will be deleted as well. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
                return '<td class="action"><a href="/fields/'. $field->DT_RowId.'/edit"><i class="fa fa-edit fa-2x"></i></a> '.$delete.'</td>';
            })
            ->rawColumns(['piggings','continuous','batch','composition','analysis','files','close','action'])
            ->make(true);
    }

    public function getCloseOut(Request $request)
    {
        $field = Field::find($request->field_id);
        $injectionsContinous = $field->injections()->where(
            \DB::raw("DATE_FORMAT(date, '%Y-%m')"),
            date('Y-m', strtotime(date('Y-m')." -1 month")))
            ->where('status', '=', Injection::STATUS_ENABLED)
            ->where('type', '=', Injection::TYPE_CONTINUOUS)
            ->get();
        // return dd($injectionsContinous);
        $injectionsBatch = $field->injections()->where(
            \DB::raw("DATE_FORMAT(date, '%Y-%m')"),
            date('Y-m', strtotime(date('Y-m')." -1 month")))
            ->where('status', '=', Injection::STATUS_ENABLED)
            ->where('type', '=', Injection::TYPE_BATCH)
            ->get();

        Injection::closeOut($injectionsContinous, $injectionsBatch);

        return redirect()->back();
    }

    public function getDelete($id)
    {
        $field = Field::find($id);
        $area = $field->area_id;
        $field->delete();
        return redirect('fields?area_id='. $area)->withMessage('Field Deleted.');
    }

    /**
     * Used for piggin select2 box.
     *
     */
    public function jsonLocations(Request $request, Field $field)
    {
        return $field->locations()
                     ->where('name', 'LIKE', '%'.$request->q.'%')
                     ->get()
                     ->map(function($location) {
                        $location->text = $location->name;
                        return $location;
                     })
                     ->toJson();
    }
}
