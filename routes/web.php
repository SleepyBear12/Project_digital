<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS & Transactions (accessible by admin and kasir)
    Route::get('/pos', [TransactionController::class, 'pos'])->name('pos');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    // Products (admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::post('/products/scan', [ProductController::class, 'scan'])->name('products.scan');
    });

    // Reports (admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    });
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

