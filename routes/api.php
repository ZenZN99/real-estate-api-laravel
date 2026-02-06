<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\BookingController;

Route::post('/auth/signup', [UserController::class, 'signup']);
Route::post('/auth/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [UserController::class, 'me']);
    Route::post('/update/profile', [UserController::class, 'updateProfile']);
    Route::middleware('auth:sanctum')->post('/update/profile', [UserController::class, 'updateProfile']);
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/user/{id}', [UserController::class, 'getUserById']);

    Route::middleware('admin')->group(function () {
        Route::post('/user/{id}/role', [UserController::class, 'updateUserRole']);
        Route::delete('/user/{id}', [UserController::class, 'deleteUserById']);
    });

    Route::apiResource('/properties', PropertyController::class);

    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/myBookings', [BookingController::class, 'myBookings']);
    Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatus']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']); 
});
