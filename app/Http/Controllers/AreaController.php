<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use BrowserDetect;

use DB;
Use Datatables;
use Auth;
use Arrow\Area;

class AreaController extends Controller
{
    public function getIndex()
    {
        if(BrowserDetect::isMobile())
            return view('areas.mobile');
        return view('areas.index');
    }

    public function getCreate(Request $request)
    {
        $area = new Area;
        $button = "Create Area";
        $action = "AreaController@postStore";
        return view('areas.form', compact('area', 'button', 'action'));
    }

    public function getEdit($id)
    {
        $area = Area::find($id);
        $this->isAuthorized($area->id, 'areas');
        $button = "Update Area";
        $action = "AreaController@putStore";
        $area_id = $area->area_id;
        return view('areas.form', compact('area', 'area_id', 'button', 'action'));
    }

    public function putStore(Request $request)
    {
        $area = Area::find($request->area_id);
        $area->name = $request->name;
        $area->save();
        return redirect()->action('AreaController@getIndex');
    }

    public function postStore(Request $request)
    {
        $area = Area::create(['name' => $request->name, 'company_id' => Auth::user()->activeCompany()->id]);

        return redirect()->action('AreaController@getIndex');
    }

    public function postData(Request $request)
    {
        $ids = array();
        $areas = Auth::user()->areas->toArray();
        foreach ($areas as $area) {
            if ($area['company_id'] == Auth::user()->activeCompany()->id) {
                $ids[] = $area['id'];
            }
        }
        $areas = DB::table('areas')
            ->leftJoin('fields', DB::raw('fields.area_id'), '=', DB::raw('areas.id'))
            ->leftJoin('area_user', DB::raw('areas.id'), '=', DB::raw('area_user.area_id'))
            ->where('areas.company_id', '=', Auth::user()->activeCompany()->id);

        if (!empty($ids)) {
            $areas->whereIn('areas.id', $ids);
        }

        $areas->select([DB::raw('areas.id as DT_RowId'), DB::raw('areas.name'), DB::raw('count(distinct fields.id) as field_count')])
            ->groupBy('areas.id');

        return Datatables::of($areas)
            ->addColumn('piggings', function($area){
                return '<td class="action"><a href="/piggings?type=area&id='. $area->DT_RowId .'" class="btn btn-info">View Pig Runs</a></td>';
            })
            ->addColumn('continuous', function($area){
                return '<td class="action"><a href="/injections/continuous?type=area&id='. $area->DT_RowId .'" class="btn btn-success">View Continuous</a></td>';
            })
            ->addColumn('batch', function($area){
                return '<td class="action"><a href="/injections/batch?type=area&id='. $area->DT_RowId .'" class="btn btn-warning">View Batch</a></td>';
            })
            ->addColumn('composition', function($area) {
                return '<td class="action"><a href="/composition?type=area&id='. $area->DT_RowId .'" class="btn btn-info">Composition</a></td>';
            })
            ->addColumn('analysis', function($area) {
                return '<td class="action"><a href="/analysis?type=area&id='. $area->DT_RowId .'" class="btn btn-success">Analysis</a></td>';
            })
            ->addColumn('files', function($area) {
                return '<td class="action"><a href="/files?type=area&id='. $area->DT_RowId .'" class="btn btn-warning">Files</a></td>';
            })
            ->addColumn('action', function($area){
                $delete = \Auth::user()->admin ? '<a href="/areas/delete/'. $area->DT_RowId.'" onclick="javascript:if(window.confirm(\'If you delete Area then all the locations and data associated with it will be deleted as well. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
                return '<td class="action"><a href="/areas/edit/'. $area->DT_RowId.'"><i class="fa fa-edit fa-2x"></i></a> '.$delete.'</td>';
            })
            ->rawColumns(['piggings','continuous','batch','composition','analysis','files','action'])
            ->make(true);
    }

    public function getDelete($id)
    {
        Area::find($id)->delete();
        return redirect()->action('AreaController@getIndex');
    }

    public function jsonFields(Area $area)
    {
        return $area->fields;
    }
}
