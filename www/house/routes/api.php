<?php

use Illuminate\Http\Request;
//use Illuminate\Routing\Route;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix'=>'producer', 'middleware' => 'auth:api'], function(){
    Route::resource('channel', 'producerController');
    Route::post('channel/{id}/item', 'producerController@addItem');
});

Route::group(['prefix'=>'consumer', 'middleware' => 'auth:api'], function() {
    Route::get('channel/{id}/{from}/{to}', 'consumerController@showRange');
});