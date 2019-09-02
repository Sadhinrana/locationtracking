<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes(['verify' => true]);

// User routes here
Route::get('/userProfile', 'UserController@userProfile');
Route::post('/userProfileUpdate', 'UserController@userProfileUpdate');
Route::get('/user-auth-check', 'UserController@AuthCheck');

// Vue route
Route::get( '/{vue_route?}', 'HomeController@index' )->where( 'vue_route', '(.*)' );