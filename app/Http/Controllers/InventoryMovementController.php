<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductRequest;

class InventoryMovementController extends Controller
{
    public function index(Request $request)
{
    $query = InventoryMovement::with('product')->latest();
    
    // Filtro por tipo
    if ($request->has('type') && $request->type != '') {
        $query->where('type', $request->type);
    }
    
    // Filtro por fechas
    if ($request->has('start_date') && $request->start_date != '') {
        $query->whereDate('created_at', '>=', $request->start_date);
    }
    
    if ($request->has('end_date') && $request->end_date != '') {
        $query->whereDate('created_at', '<=', $request->end_date);
    }
    
    $movements = $query->paginate(10);
    
    // Mantener los parámetros de filtro en la paginación
    if ($request->anyFilled(['type', 'start_date', 'end_date'])) {
        $movements->appends($request->all());
    }
    
    return view('movements.index', compact('movements'));
}

    public function create()
    {
        $products = Product::all();
        return view('movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:entrada,salida',
            'receptor'=> 'required',
            'notes' => 'nullable'
        ]);

        // Crear el movimiento
        $movement = InventoryMovement::create($request->all());
        
        // Actualizar el stock del producto
        $product = Product::find($request->product_id);
        if ($request->type == 'entrada') {
            $product->stock += $request->quantity;
        } else {
            $product->stock -= $request->quantity;
        }
        $product->save();

        return redirect()->route('movements.index')
            ->with('success', 'Movimiento registrado exitosamente.');
    }
}