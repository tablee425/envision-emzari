<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Arrow\User;
use Arrow\Company;
use Auth;
use DB;
use Datatables;

class AccountController extends Controller
{

    public function getIndex()
    {
        //return dd($account->companies->pluck('name')->toArray());
        $accounts = User::all();
        return view('accounts.index', compact('accounts'));
    }

    public function postData(Request $request)
    {
        $users = DB::table('users')
                        ->leftJoin('company_user', DB::raw('users.id'), '=', DB::raw('company_user.user_id'))
                        ->leftJoin('companies', DB::raw('companies.id'), '=', DB::raw('company_user.company_id'))
                        ->select([DB::raw('users.id as DT_RowId'), DB::raw('users.name'), DB::raw('users.email'), 
                                  DB::raw('GROUP_CONCAT(DISTINCT companies.name SEPARATOR \', \') as companies')])
                        ->groupBy('users.id');

        return Datatables::of($users)
            ->addColumn('action', function($user){
                $delete = \Auth::user()->admin ? '<a href="/accounts/delete/'. $user->DT_RowId.'" onclick="javascript:if(window.confirm(\'If you delete User then data associated with it will be deleted as well. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>' : '';
                return '<td class="action"><a href="/accounts/edit/'. $user->DT_RowId.'"><i class="fa fa-edit fa-2x"></i></a> '.$delete.'</td>';
            })
            ->make(true);
    }

    public function getCreate()
    {
        $type = "create";
        $action = "AccountController@postCreate";
        $user = new User;
        $companies = Company::all();

        return view('accounts.form', compact('type', 'action', 'user', 'companies'));
    }

    public function postCreate(Request $request)
    {
        $user = User::create(['name' => $request->name, 'email' => $request->email, 
                              'password' => $request->password]);
        $user->companies()->attach($request->companies);
        $user->areas()->attach($request->areas);
        $user->save();
        return redirect()->action('AccountController@getIndex');
    }

    public function getEdit($user_id)
    {
        $type = "update";
        $action = "AccountController@postEdit";
        $user = User::find($user_id);
        $companies = Company::all();

        return view('accounts.form', compact('type', 'action', 'user', 'companies'));
    }

    public function postEdit(Request $request)
    {
        // return dd($request->all());
        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $companies = is_array($request->companies) ? $request->companies : array();
        $areas = is_array($request->areas) ? $request->areas : array();
        $user->companies()->sync($companies);
        $user->areas()->sync($areas);

        return redirect()->action('AccountController@getIndex');
    }

    public function getEditPassword($user_id)
    {
        $user = User::find($user_id);
        
        return view('accounts.password', compact('user'));
    }

    public function postEditPassword(Request $request)
    {
        $user = User::find($request->user_id);
        $user->update(['password' => $request->password]);

        return redirect()->action('AccountController@getIndex');
    }

    public function getSettings()
    {
        $companies = Auth::user()->companies;
        return view('accounts.settings', compact('companies'));
    }

    public function postSettings(Request $request)
    {
        $user = Auth::user();
        $user->active_company = $request->company_id;
        $user->save();

        return redirect()->back();
    }

    public function getDelete($id)
    {
        User::find($id)->delete();
        return redirect()->action('AccountController@getIndex');
    }
}
