<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::auth();

Route::get('login', ['as' => 'login', function () {
    return view('login');
}]);

Route::post('login', 'AuthController@login');

Route::get('logout', ['as' => 'logout', function() {
    Auth::logout();
    return redirect()->route('login');
}]);

Route::get('/', function() {
    return redirect()->route('dashboard');
});

Route::group(['middleware' => 'auth'], function() {

    Route::get('dashboard', ['as' => 'dashboard', function() {
        return view('dashboard');
    }]);

    Route::group(['prefix' => 'excel'], function() {
        Route::get('/', 'ExcelReportController@getIndex');
        Route::post('report', 'ExcelReportController@postReport');
    });

    Route::group(['prefix' => 'fields'], function() {
        Route::get('/', 'FieldController@index');
        Route::get('create', 'FieldController@showCreateFieldForm');
        Route::get('{id}/edit', 'FieldController@showEditFieldForm');
        Route::put('{field}', 'FieldController@update');
        Route::post('/', 'FieldController@store');
        Route::post('data', 'FieldController@postData');
        Route::get('close-out', 'FieldController@getCloseOut');
        Route::get('delete/{id}', 'FieldController@getDelete');
        Route::get('locations/{field}', 'FieldController@jsonLocations');
    });

    Route::group(['prefix' => 'locations'], function() {
        Route::get('/', 'LocationController@getIndex');
        Route::get('create', 'LocationController@getCreate');
        Route::get('edit/{id}', 'LocationController@getEdit');
        Route::put('store', 'LocationController@putStore');
        Route::post('store', 'LocationController@postStore');
        Route::post('data', 'LocationController@postData');
        Route::get('delete/{id}', 'LocationController@getDelete');
        Route::get('{location}/chemicals', 'LocationController@listChemicals');
    });

    Route::group(['prefix' => 'imports'], function() {
        Route::get('/', 'ImportController@getIndex');
        Route::get('errors', 'ImportController@getErrors');
        Route::post('end-of-month-import', 'ImportController@endOfMonthInventory');
        Route::post('production', 'ImportController@production');
    });

    Route::group(['prefix' => 'injections'], function() {
        Route::get('continuous', 'InjectionController@getContinuous');
        Route::get('batch', 'InjectionController@getBatch');
        Route::get('create', 'InjectionController@getCreate');
        Route::get('delete/{id}', 'InjectionController@getDelete');
        Route::post('batch-create', 'InjectionController@postBatchCreate');
        Route::post('continuous-create', 'InjectionController@postContinuousCreate');
        Route::get('edit/{id}', 'InjectionController@getEdit');
        Route::post('batch-data', 'InjectionController@postBatchData');
        Route::post('continuous-data', 'InjectionController@postContinuousData');
        Route::post('continuous-update', 'InjectionController@postContinuousUpdate');
        Route::post('batch-update', 'InjectionController@postBatchUpdate');
        Route::post('standard-batch-update', 'InjectionController@postStandardBatchUpdate');
        Route::post('standard-continuous-update', 'InjectionController@postStandardContinuousUpdate');
    });

    Route::group(['prefix' => 'areas'], function() {
        Route::get('{area}/fields', 'AreaController@jsonFields');
        Route::get('/', 'AreaController@getIndex');
        Route::get('create', 'AreaController@getCreate');
        Route::get('edit/{id}', 'AreaController@getEdit');
        Route::put('store', 'AreaController@putStore');
        Route::post('store', 'AreaController@postStore');
        Route::post('data', 'AreaController@postData');
        Route::get('delete/{id}', 'AreaController@getDelete');
    });

    Route::group(['prefix' => 'piggings'], function() {
        Route::get('/', 'PiggingController@getIndex');
        Route::get('create', 'PiggingController@getCreate');
        Route::post('create', 'PiggingController@postCreate');
        Route::get('edit/{id}', 'PiggingController@getEdit');
        Route::post('update', 'PiggingController@postUpdate');
        Route::post('table-update', 'PiggingController@postTableUpdate');
        Route::post('data', 'PiggingController@postData');
        Route::get('delete/{id}', 'PiggingController@getDelete');
    });

    Route::group(['prefix' => 'reports'], function() {
        Route::get('/', 'ReportController@getIndex');
        Route::post('filter', 'ReportController@postFilter');
    });

    Route::group(['prefix' => 'analysis'], function() {
        Route::get('/', 'AnalysisController@getIndex');
        Route::get('create', 'AnalysisController@getCreate');
        Route::post('update', 'AnalysisController@postUpdate');
        Route::post('store', 'AnalysisController@postStore');
        Route::get('edit/{id}', 'AnalysisController@getEdit');
        Route::post('data', 'AnalysisController@postData');
    });

    Route::group(['prefix' => 'composition'], function() {
        Route::get('/', 'CompositionController@getIndex');
        Route::get('create', 'CompositionController@getCreate');
        Route::post('update', 'CompositionController@postUpdate');
        Route::post('store', 'CompositionController@postStore');
        Route::get('edit/{id}', 'CompositionController@getEdit');
        Route::post('data', 'CompositionController@postData');
    });

    Route::group(['prefix' => 'delivery-tickets'], function() {
        Route::get('/', 'DeliveryTicketController@getIndex');
        Route::get('area-selection', 'DeliveryTicketController@getAreaSelection');
        Route::get('create', 'DeliveryTicketController@getCreate');
        Route::get('delete/{id}', 'DeliveryTicketController@getDelete');
        Route::get('{ticket}/edit', 'DeliveryTicketController@edit');
        Route::put('{ticket}', 'DeliveryTicketController@update');
        Route::post('store', 'DeliveryTicketController@postStore');
        Route::post('data', 'DeliveryTicketController@postData');
    });

    Route::group(['prefix' => 'companies'], function() {
        Route::get('/', 'CompanyController@getIndex');
        Route::get('create', 'CompanyController@getCreate');
        Route::post('create', 'CompanyController@postCreate');
        Route::get('edit/{company_id}', 'CompanyController@getEdit');
        Route::get('delete/{company_id}', 'CompanyController@getDelete');
        Route::post('edit', 'CompanyController@postEdit');
        Route::get('all-locations', 'CompanyController@getAllLocations');
        Route::get('locations-by-costcentre', 'CompanyController@getLocationsByCostcentre');
        Route::get('all-fields', 'CompanyController@getAllFields');
        Route::post('data', 'CompanyController@postData');
    });

    Route::group(['prefix' => 'files'], function() {
        Route::get('/', 'UploadController@getIndex');
        Route::get('new/{location_id}', 'UploadController@getNew');
        Route::get('download/{id}', 'UploadController@getDownload');
        Route::post('file-upload', 'UploadController@postFileUpload');
        Route::post('data', 'UploadController@postData');
    });

    Route::get('delivery-tickets-doc/{ticket}/report-uwi', 'DeliveryTicketDocumentController@generateTicketWithUWI');
    Route::get('delivery-tickets-doc/{ticket}/report', 'DeliveryTicketDocumentController@generateTicketWithoutUWI');
    Route::get('delivery-tickets-excel/{ticket}/report','DeliveryTicketDocumentController@exportExcel');

    Route::post('import/deliveries', 'DeliveryImportController@update');

    Route::group(['prefix' => 'closeout'], function() {
        Route::post('/', 'CloseOutController@process');
        Route::get('{field}', 'CloseOutController@datePicker');
    });

    Route::group(['prefix' => 'pig-runs'], function() {
        Route::get('/', 'PigRunController@index');
        Route::post('/', 'PigRunController@postData');
        Route::get('edit/{run_type}/{pigging}', 'PigRunController@edit');
        Route::put('/{pigging}', 'PigRunController@update');
    });

    Route::group(['prefix' => 'accounts'], function() {
        Route::get('settings', 'AccountController@getSettings');
        Route::post('settings', 'AccountController@postSettings');
    });

    Route::group(['middleware' => 'admin'], function() {
        Route::group(['prefix' => 'accounts'], function() {
            Route::get('/', 'AccountController@getIndex');
            Route::get('create', 'AccountController@getCreate');
            Route::get('delete/{id}', 'AccountController@getDelete');
            Route::get('edit/{id}', 'AccountController@getEdit');
            Route::get('edit-password/{id}', 'AccountController@getEditPassword');

            Route::post('data', 'AccountController@postData');
            Route::post('create', 'AccountController@postCreate');
            Route::post('edit', 'AccountController@postEdit');
            Route::post('edit-password', 'AccountController@postEditPassword');
        });
    });

});

