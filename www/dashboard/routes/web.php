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

use Illuminate\Support\Facades\Storage;

Route::get('/', 'ChartController@index');
Route::get('/token/{token}', function ($token){
    Storage::disk('local')->put('file.txt', $token);
    return redirect('/');
});
Route::get('/token', function (){
    dump(Storage::disk('local')->get('file.txt'));
});
//Route::group(['prefix'=>'api'], function(){
Route::resource('channel', 'houseController');
Route::post('channel/{id}/item', 'houseController@addItem');
Route::get('channel/{id}/{from}/{to}', 'houseController@showRange');
//});