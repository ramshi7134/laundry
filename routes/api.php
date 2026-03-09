<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\DeliveryController;
use App\Http\Controllers\API\ExpenseController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\SyncController;

// ── Public ────────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

// ── Authenticated ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',    [AuthController::class, 'user']);

    // Customers
    Route::apiResource('customers', CustomerController::class);

    // Services
    Route::apiResource('services', ServiceController::class);

    // Orders
    Route::apiResource('orders', OrderController::class);
    Route::post('/orders/{order}/status',      [OrderController::class, 'updateStatus']);
    Route::post('/orders/{order}/payments',    [OrderController::class, 'addPayment']);

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/daily',              [ReportController::class, 'daily']);
        Route::get('/monthly',            [ReportController::class, 'monthly']);
        Route::get('/revenue-by-service', [ReportController::class, 'revenueByService']);
        Route::get('/branch-summary',     [ReportController::class, 'branchSummary']);
    });

    // Delivery
    Route::get('/deliveries',                         [DeliveryController::class, 'index']);
    Route::post('/deliveries',                        [DeliveryController::class, 'store']);
    Route::get('/deliveries/{delivery}',              [DeliveryController::class, 'show']);
    Route::post('/deliveries/{delivery}/status',      [DeliveryController::class, 'updateStatus']);

    // Expenses
    Route::get('/expenses/categories',  [ExpenseController::class, 'categories']);
    Route::apiResource('expenses', ExpenseController::class);

    // Inventory
    Route::get('/inventory',                          [InventoryController::class, 'index']);
    Route::post('/inventory',                         [InventoryController::class, 'store']);
    Route::get('/inventory/low-stock',                [InventoryController::class, 'lowStock']);
    Route::get('/inventory/{item}',                   [InventoryController::class, 'show']);
    Route::put('/inventory/{item}',                   [InventoryController::class, 'update']);
    Route::post('/inventory/{item}/adjust',           [InventoryController::class, 'adjustStock']);

    // Wallet & Loyalty
    Route::get('/customers/{customer}/wallet',                    [WalletController::class, 'walletShow']);
    Route::post('/customers/{customer}/wallet/credit',            [WalletController::class, 'walletCredit']);
    Route::get('/customers/{customer}/wallet/transactions',       [WalletController::class, 'walletTransactions']);
    Route::get('/customers/{customer}/loyalty/balance',           [WalletController::class, 'loyaltyBalance']);
    Route::get('/customers/{customer}/loyalty/history',           [WalletController::class, 'loyaltyHistory']);
    Route::post('/customers/{customer}/loyalty/adjust',           [WalletController::class, 'loyaltyAdjust']);

    // Settings
    Route::get('/settings',          [SettingController::class, 'index']);
    Route::post('/settings',         [SettingController::class, 'update']);
    Route::get('/settings/{key}',    [SettingController::class, 'show']);

    // Cloud Sync
    Route::get('/sync/pull',    [SyncController::class, 'pull']);
    Route::post('/sync/push',   [SyncController::class, 'push']);
    Route::get('/sync/status',  [SyncController::class, 'status']);
    Route::post('/sync/retry',  [SyncController::class, 'retry']);
});
