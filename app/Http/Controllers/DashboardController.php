<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\ProductRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   // App/Http/Controllers/DashboardController.php
// App/Http/Controllers/DashboardController.php
public function index()
{
    $totalProducts = Product::count();
    $totalEntries = InventoryMovement::where('type', 'entrada')->count();
    $totalOutputs = InventoryMovement::where('type', 'salida')->count();
    $pendingRequestsCount = ProductRequest::where('status', 'pendiente')->count();
    $recentMovements = InventoryMovement::with('product')->latest()->take(5)->get();
    
    // ✅ SOLO CONTAR, no traer todos los productos
    $lowStockCount = Product::where('stock', '<', 5)->count();

    return view('dashboard', compact(
        'totalProducts',
        'totalEntries', 
        'totalOutputs',
        'pendingRequestsCount',
        'recentMovements',
        'lowStockCount' // ← Solo el conteo
    ));
}
}