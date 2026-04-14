<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\SeatController;
use App\Http\Controllers\API\ShowtimeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    // Public routes
    Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
    Route::apiResource('showtimes', ShowtimeController::class)->only(['index', 'show']);
    Route::get('showtimes/{showtime}/seats', [SeatController::class, 'getSeatsByShowtime']);

    // Auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes - use web session + sanctum token authentication
    Route::middleware(['web', 'auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // Movies (create, update, delete)
        Route::apiResource('movies', MovieController::class)->only(['store', 'update', 'destroy']);

        // Showtimes (create, update, delete)
        Route::apiResource('showtimes', ShowtimeController::class)->only(['store', 'update', 'destroy']);

        // Bookings
        Route::apiResource('bookings', BookingController::class)->except(['update', 'destroy']);
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);

        // Payments
        Route::post('payments', [PaymentController::class, 'store']);
        Route::get('payments/{payment}', [PaymentController::class, 'show']);
    });
});