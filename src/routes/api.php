<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\User\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('/token', [AuthController::class, 'token']);

// Register
Route::post('/register', [UserController::class, 'register']);

// User
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [UserController::class, 'me']);
});

// Event
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/event', [EventController::class, 'create']);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/event/{event}', [EventController::class, 'show']);
    Route::post('/event/{event}/join', [EventController::class, 'join']);
    Route::post('/event/{event}/leave', [EventController::class, 'leave']);
    Route::delete('/event/{event}', [EventController::class, 'destroy']);
});


