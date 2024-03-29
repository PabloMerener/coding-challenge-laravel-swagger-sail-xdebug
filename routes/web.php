<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;
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

Route::resource('players', PlayerController::class);
Route::post('/tournaments/test', [TournamentController::class, 'test']);
Route::get('/tournaments/results', [TournamentController::class, 'results']);
Route::resource('tournaments', TournamentController::class);
