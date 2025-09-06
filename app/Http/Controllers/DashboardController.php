<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\ProductRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalEntries = InventoryMovement::where('type', 'entrada')->count();
        $totalOutputs = InventoryMovement::where('type', 'salida')->count();
        
        // Obtener los últimos movimientos con la relación del producto
        $recentMovements = InventoryMovement::with('product')
            ->latest()
            ->take(3)
            ->get();
            
        $lowStockProducts = Product::where('stock', '<=', 2)->get();

        return view('dashboard', compact(
            'totalProducts', 
            'totalEntries', 
            'totalOutputs', 
            'recentMovements',
            'lowStockProducts'
        ));
    }
}