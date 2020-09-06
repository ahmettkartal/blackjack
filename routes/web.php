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

Route::get('/', 'GameRunner@index');
Route::post('/startGame', 'GameRunner@startGame')->name("startGame");
Route::get('/game/{id}', "GameRunner@gameDetail")->name("gameDetail");
Route::post('/gameStatus', 'GameRunner@gameStatus')->name("gameStatus");
