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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => '/v1', 'namespace' => 'Api', 'as' => 'api.'], function () {
    Route::resource('users', 'UserController', ['except' => ['create', 'edit']]);
    Route::get('all_users', 'UserController@all_users');
    Route::resource('works', 'WorkController', ['except' => ['create', 'edit']]);
    Route::post('/users/update/{id}', 'UserController@updateLocationByLogout');
    Route::post('/worker-for-map', 'UserController@UserForWorkPreview');
});

