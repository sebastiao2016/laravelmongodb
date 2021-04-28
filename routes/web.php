<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('add','App\Http\Controllers\CarController@create');
Route::post('add','App\Http\Controllers\CarController@store');
Route::get('car','App\Http\Controllers\CarController@index');
Route::get('edit/{id}','App\Http\Controllers\CarController@edit');
Route::post('edit/{id}','App\Http\Controllers\CarController@update');
Route::delete('{id}','App\Http\Controllers\CarController@destroy');

Route::get('teste-frete','App\Http\Controllers\FreteController@index');
Route::get('calcular','App\Http\Controllers\FreteController@calcular');
Route::get('criar','App\Http\Controllers\FreteController@criar');
Route::get('listar','App\Http\Controllers\FreteController@listar');
Route::get('calcular_z','App\Http\Controllers\FreteController@calcular_z');

Route::get('bling-get','App\Http\Controllers\BlingController@index');
Route::get('bling-post','App\Http\Controllers\BlingController@store');
Route::get('capturar_loja','App\Http\Controllers\BlingController@capturar_loja');


Route::get('envio-email', function(){

    $user = new stdClass();
    $user->name = 'Sebastiao Web';
    $user->email = 'sebastiao.cmarques@gmail.com';

    //return new \App\Mail\newLaravelTips($user);

    Mail::queue(new \App\Mail\newLaravelTips($user));

});

