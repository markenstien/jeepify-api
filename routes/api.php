<?php

use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VehicleController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Types\Relations\Role;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('operation')->group(function(){

    Route::controller(BookingController::class)->group(function() {
        Route::get('/booking/pickup-dropoff', 'getPickupDropOff');
        Route::post('/booking/pickup-dropoff', 'pickupDropOff');
        Route::get('/booking/{id}', 'get');
        Route::post('/booking/accept', 'accept');
        Route::post('/booking/pickup', 'pickup');
        Route::get('/booking', 'index');
        Route::post('/booking', 'store');
    });

    Route::controller(VehicleController::class)->group(function() {
        Route::get('/vehicle//', 'index');
        Route::get('/vehicle/{id}', 'get');
        Route::get('/vehicle/get-user/{id}', 'getUserVehicle');
        Route::post('/vehicle/register', 'register');
    });
});


Route::prefix('accounts')->group(function(){
    Route::post('/user/register', [UserController::class, 'register']);
    Route::get('/user/{id}', [UserController::class, 'get']);
    Route::get('/user//', [UserController::class, 'all']);
    Route::post('/user/authenticate', [UserController::class, 'authenticate']);
});