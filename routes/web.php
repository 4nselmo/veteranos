<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('getJogadores', 'App\Http\Controllers\HomeController@getJogadores');
Route::post('salvarJogador', 'App\Http\Controllers\HomeController@salvarJogador');
Route::post('atualizarJogador', 'App\Http\Controllers\HomeController@atualizarJogador');
Route::get('getTemporadas', 'App\Http\Controllers\HomeController@getTemporadas');
Route::get('getDados', 'App\Http\Controllers\HomeController@getDados');
Route::get('getGolsJogador', 'App\Http\Controllers\HomeController@getGolsJogador');
Route::post('storeGol', 'App\Http\Controllers\HomeController@storeGol');
Route::post('updateGol', 'App\Http\Controllers\HomeController@updateGol');
Route::get('getEstatisticas', 'App\Http\Controllers\HomeController@getEstatisticas');
Route::post('deleteJogador', 'App\Http\Controllers\HomeController@deleteJogador');
Route::post('deleteGols', 'App\Http\Controllers\HomeController@deleteGols');
Route::get('listaGolsPdf', 'App\Http\Controllers\HomeController@gerarPdf');
Route::get('financeiro', 'App\Http\Controllers\FinanceiroController@index');
Route::get('getDadosFinanceiro', 'App\Http\Controllers\FinanceiroController@getDados');
Route::post('storeUpdateContribuicao', 'App\Http\Controllers\FinanceiroController@storeUpdateContribuicao');
Route::post('storeUpdateFinanceiroLanacamento', 'App\Http\Controllers\FinanceiroController@storeUpdateFinanceiroLanacamento');
Route::get('getContribuicoes', 'App\Http\Controllers\FinanceiroController@getContribuicoes');
Route::get('financeiroPdf', 'App\Http\Controllers\FinanceiroController@gerarPdf');


