<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'clients'], function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::delete('/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    Route::delete('/{client}/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    Route::get('/{client}/journals/create', [JournalController::class, 'create'])->name('journals.create');
    Route::get('/{client}/journals/{journal}', [JournalController::class, 'show'])->name('journals.show');
    Route::post('/{client}/journals', [JournalController::class, 'store'])->name('journals.store');
    Route::delete('/{client}/journals/{journal}', [JournalController::class, 'destroy'])->name('journals.destroy');
});
