<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductRequestController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ClientRequestController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ✅ RUTAS PÚBLICAS
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rutas de clientes (públicas)
Route::prefix('client')->group(function () {
    Route::get('solicitud', [ClientRequestController::class, 'create'])->name('client.requests.create');
    Route::post('solicitud', [ClientRequestController::class, 'store'])->name('client.requests.store');
    Route::get('solicitudes', [ClientRequestController::class, 'index'])->name('client.requests.index');
});

// ✅ RUTAS PROTEGIDAS (requieren login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('movements', InventoryMovementController::class)->except(['show']);
    Route::get('/movements/pdf', [InventoryMovementController::class, 'generatePDF'])->name('movements.pdf');

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('requests', [ProductRequestController::class, 'index'])->name('admin.requests.index');
        Route::post('requests/{id}/review', [ProductRequestController::class, 'markAsReview'])->name('admin.requests.review');
        Route::post('requests/{id}/approve', [ProductRequestController::class, 'approve'])->name('admin.requests.approve');
        Route::post('requests/{id}/reject', [ProductRequestController::class, 'reject'])->name('admin.requests.reject');
        Route::post('requests/{id}/create-product', [ProductRequestController::class, 'createProductFromRequest'])->name('admin.requests.create-product');
        Route::post('requests/{id}/complete', [ProductRequestController::class, 'completePending'])->name('admin.requests.complete');
    });
});