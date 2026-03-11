<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\POS\PosController;
use App\Http\Controllers\Web\CustomerWebController;
use App\Http\Controllers\Web\CloudController;
use App\Http\Controllers\LicenseController;

Route::get('/', function () {
    return view('welcome');
});

// Licensing Routes
Route::get('/license/activate', [LicenseController::class, 'showActivateForm'])->name('license.activate');
Route::post('/license/verify', [LicenseController::class, 'verify'])->name('license.verify');

// Protected POS Routes
Route::middleware(['license'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
});

// Customer Tracking App
Route::get('/customer/login', [CustomerWebController::class, 'login'])->name('customer.login');
Route::get('/customer/orders', [CustomerWebController::class, 'orders'])->name('customer.orders');

// Cloud Admin Dashboard
Route::get('/cloud', [CloudController::class, 'index'])->name('cloud.index');
