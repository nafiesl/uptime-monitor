<?php

use App\Http\Controllers\MonitoringController;
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

Route::view('/', 'auth.login')->middleware('guest');

Auth::routes(['register' => false, 'reset' => false]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

    Route::get('/timeline', [MonitoringController::class, 'timeline'])->name('timeline');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
