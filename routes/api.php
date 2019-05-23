<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/login', 'AppLoginController@applogin');
Route::post('auth/user', 'AppLoginController@tokenlogin');
Route::post('auth/logout', 'AppLoginController@logout');

Route::post('area/fetch', 'AppLoginController@getAreas');
Route::post('field/fetch', 'AppLoginController@getFields');
Route::post('piggings/update/multiple', 'AppLoginController@multipleUpdate');
Route::post('piggings/fetch', 'AppLoginController@getPiggings');
Route::post('piggings/fetch/all', 'AppLoginController@getAllPigings');
Route::post('piggings/update', 'AppLoginController@updatePiggings');
Route::get('fetch/all', 'AppLoginController@getOnlineDatas');
