<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductRequestController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ClientRequestController;
use Illuminate\Support\Facades\Route;

// Rutas principales
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('products', ProductController::class);
Route::resource('movements', InventoryMovementController::class);

// Rutas para administradores (solicitudes)
Route::prefix('admin')->group(function () {
    Route::get('requests', [ProductRequestController::class, 'index'])->name('admin.requests.index');
    // AGREGAR ESTA RUTA ↓↓↓
    Route::post('requests/{id}/review', [ProductRequestController::class, 'markAsReview'])->name('admin.requests.review');
    // RUTAS EXISTENTES
    Route::post('requests/{id}/approve', [ProductRequestController::class, 'approve'])->name('admin.requests.approve');
    Route::post('requests/{id}/reject', [ProductRequestController::class, 'reject'])->name('admin.requests.reject');
    Route::post('requests/{id}/create-product', [ProductRequestController::class, 'createProductFromRequest'])->name('admin.requests.create-product');
     Route::post('requests/{id}/complete', [ProductRequestController::class, 'completePending'])->name('admin.requests.complete');
});

// Rutas para clientes (acceso externo) - SIN LOGIN
Route::prefix('client')->group(function () {
    Route::get('solicitud', [ClientRequestController::class, 'create'])->name('client.requests.create');
    Route::post('solicitud', [ClientRequestController::class, 'store'])->name('client.requests.store');
    Route::get('solicitudes', [ClientRequestController::class, 'index'])->name('client.requests.index');
});
