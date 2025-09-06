<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function index()
    {
        $movements = InventoryMovement::with('product')->latest()->paginate(10);
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