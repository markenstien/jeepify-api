<?php

use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('operation')->group(function(){
    Route::get('/booking/pickup-dropoff', [BookingController::class, 'getPickupDropOff']);
    Route::post('/booking/pickup-dropoff', [BookingController::class, 'pickupDropOff']);
    
    Route::get('/booking/{id}', [BookingController::class, 'get']);
    Route::post('/booking/accept', [BookingController::class, 'accept']);
    Route::post('/booking/pickup', [BookingController::class, 'pickup']);

    Route::get('/booking', [BookingController::class, 'index']);
    Route::post('/booking', [BookingController::class, 'store']);
});


Route::prefix('accounts')->group(function(){
    Route::post('/user/register', [UserController::class, 'register']);
    Route::get('/user/{id}', [UserController::class, 'get']);
    Route::get('/user//', [UserController::class, 'all']);
    Route::post('/user/authenticate', [UserController::class, 'authenticate']);
});