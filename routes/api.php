<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('services', ServiceController::class);
    
    Route::apiResource('orders', OrderController::class);
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    Route::prefix('reports')->group(function () {
        Route::get('/daily', [ReportController::class, 'daily']);
        Route::get('/monthly', [ReportController::class, 'monthly']);
    });
});
