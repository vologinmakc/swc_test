<?php

use App\Http\Controllers\Api\Event\EventController;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [\App\Http\Controllers\Panel\AdminController::class, 'index'])->name('admin.dashboard');
});

// Event
Route::middleware(['auth'])->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/event/{event}', [EventController::class, 'show']);
    Route::post('/event/{event}/join', [EventController::class, 'join']);
    Route::post('/event/{event}/leave', [EventController::class, 'leave']);
});
