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

Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::get('get-ip', 'AuthController@getIP');

Route::post('images/store', 'ImageController@store');
Route::delete('images/destroy', 'ImageController@destroy');

Route::post('pages/reorder', 'PageController@reorder');

Route::apiResources([
    'pages' => 'PageController'
]);