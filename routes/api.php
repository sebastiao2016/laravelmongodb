<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('buscaCliente','App\Http\Controllers\SigepController@buscaCliente');
Route::get('consultaCep','App\Http\Controllers\SigepController@consultaCep');
Route::get('calcPrecoPrazo','App\Http\Controllers\SigepController@calcPrecoPrazo');
Route::get('cliqueRetire','App\Http\Controllers\SigepController@clique_retire');
Route::get('imprimirEtiquetas2016','App\Http\Controllers\SigepController@imprimirEtiquetas2016');
Route::get('imprimirPlp','App\Http\Controllers\SigepController@imprimirPlp');

