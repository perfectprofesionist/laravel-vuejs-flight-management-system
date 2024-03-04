<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightApiController;

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

Route::get('/', function () {
    return view('app');
});
Route::get('/api-get-flight-data',[FlightApiController::class, 'getFlightData'])->name('get-flight-data');
// Route::get('/showvue',[App\Http\Controllers\VueController::class, 'showComponent'])->name('appvue');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

