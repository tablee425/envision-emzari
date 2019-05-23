<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use DB;
use Datatables;
use Arrow\Company;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => [
            'getLocationsByCostcentre',
            'getAllLocations'
        ]]);
    }

    public function getIndex()
    {
        return view('companies.index');
    }

    public function getCreate()
    {
        $type = "create";
        $action = "CompanyController@postCreate";
        $company = new Company;

        return view('companies.form', compact('type', 'action', 'company'));
    }

    public function postCreate(Request $request)
    {
        $company = Company::create(['name' => $request->name]);
        return redirect()->action('CompanyController@getIndex');
    }

    public function getEdit($company_id)
    {
        $type = "update";
        $action = "CompanyController@postEdit";
        $company = Company::find($company_id);

        return view('companies.form', compact('type', 'action', 'company'));
    }

    public function postEdit(Request $request)
    {
        $company = Company::find($request->company_id);
        $company->update(['name' => $request->name]);

        if($request->hasFile('file')){
            $company->update(['logo_extension' => $request->file->getClientOriginalExtension()]);
            $request->file('file')->move(public_path().'/logos', $company->id.'.'.$company->logo_extension);
        }

        return redirect()->action('CompanyController@getIndex');
    }
    public function getAllLocations(Request $request)
    {
        $data = [];
        $items = auth()->user()->activeCompany()->locations($request->q)->all(); // ->where('name','like', $request->q)->get();
        // $data['items'] = $items;
        // $data['count'] = $items->count();
        return response()->json($items);
    }
    /**
     * Select2 Box response for creating a Delivery Ticket
     *
     */
    public function getLocationsByCostcentre(Request $request)
    {
        $items = auth()->user()->activeCompany()->locationsByCostcentre($request->q, $request->area_id)->all();
        return response()->json($items);
    }

    public function getAllFields(Request $request)
    {
        $data = [];
        $items = auth()->user()->activeCompany()->fields($request->q)->where('fields.name','LIKE', '%'.$request->q.'%')->get(); // ->where('name','like', $request->q)->get();
        // $data['items'] = $items;
        // $data['count'] = $items->count();
        return response()->json($items);
    }

    public function postData(Request $request)
    {
        $companies = DB::table('companies')
                        ->select(['id as DT_RowId', 'name']);

        return Datatables::of($companies)
            ->addColumn('action', function($company){
                $delete =  \Auth::user()->admin ? '<a href="/companies/delete/'. $company->DT_RowId.'" onclick="javascript:if(window.confirm(\'If you delete Company then all the locations and data associated with it will be deleted as well. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
                return '<td class="action"><a href="/companies/edit/'. $company->DT_RowId.'"><i class="fa fa-edit fa-2x"></i></a> '.$delete.'</td>';
            })
            ->make(true);
    }

    public function getDelete($company_id)
    {
        Company::find($company_id)->delete();
        return redirect()->action('CompanyController@getIndex');
    }
}
