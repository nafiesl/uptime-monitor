<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\CustomerSiteController;
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
    Route::get('/home', [MonitoringController::class, 'index'])->name('home');

    Route::get('customer_sites/{customer_site}/timeline', [CustomerSiteController::class, 'timeline'])
        ->name('customer_sites.timeline');
    Route::resource('customer_sites', CustomerSiteController::class);

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

/*
 * Vendors Routes
 */
Route::resource('vendors', App\Http\Controllers\VendorController::class);
