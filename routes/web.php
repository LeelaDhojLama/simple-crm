<?php

use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages/dashboard');
    });

    Route::get('/sales', function () {
        return view('pages/sales');
    });

    Route::get('/customers', function () {
        return view('pages/customer');
    });

    Route::get('/reports', function () {
        return view('pages/report');
    });

    Route::get('change-password', [SettingsController::class, 'index']);
    Route::post('change-password', [SettingsController::class, 'store'])->name('change.password');
});



Auth::routes();
