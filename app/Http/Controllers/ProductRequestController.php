<?php

namespace App\Http\Controllers;

use App\Models\ProductRequest; // ← IMPORTANTE: Agregar esto
use App\Models\Product;        // ← IMPORTANTE: Agregar esto  
use App\Models\InventoryMovement; // ← IMPORTANTE: Agregar esto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRequestController extends Controller
{
    public function index()
    {
        // Ahora podemos usar los modelos directamente sin \
        $requests = ProductRequest::with('product')->latest()->paginate(10);
        return view('admin.requests.index', compact('requests'));
    }

  public function approve($id)
{
    $productRequest = ProductRequest::findOrFail($id);
    
    if ($productRequest->is_new_product && !$productRequest->product_id) {
        return redirect()->back()->with('error', 'Primero debe crear el producto desde la solicitud.');
    }
    
    $product = $productRequest->product;

    // ✅ PARA PRODUCTOS NUEVOS: ENTRADA + SALIDA
    if ($productRequest->is_new_product) {
        // 1. ENTRADA de la cantidad solicitada
        InventoryMovement::create([
            'product_id' => $product->id,
            'quantity' => $productRequest->quantity_requested, // ← LA CANTIDAD CORRECTA
            'type' => 'entrada',
            'notes' => 'Entrada inicial para producto nuevo: ' . $product->name,
            'receptor' => $productRequest->receptor
        ]);

        $product->stock += $productRequest->quantity_requested; // ← SUMAR LA CANTIDAD
        $product->save();

        // 2. SALIDA para asignar al solicitante
        InventoryMovement::create([
            'product_id' => $product->id,
            'quantity' => $productRequest->quantity_requested, // ← MISMA CANTIDAD
            'type' => 'salida',
            'notes' => 'Solicitud aprobada: ' . $productRequest->purpose,
            'receptor' => $productRequest->receptor
        ]);

        $product->stock -= $productRequest->quantity_requested; // ← RESTAR LA CANTIDAD
        $product->save();

    } else {
        // ✅ PARA PRODUCTOS EXISTENTES: SOLO SALIDA
        if ($product->stock < $productRequest->quantity_requested) {
            return redirect()->back()->with('error', 'Stock insuficiente. Disponible: ' . $product->stock);
        }

        InventoryMovement::create([
            'product_id' => $product->id,
            'quantity' => $productRequest->quantity_requested, // ← LA CANTIDAD
            'type' => 'salida',
            'notes' => 'Solicitud aprobada: ' . $productRequest->purpose,
            'receptor' => $productRequest->receptor
        ]);

        $product->stock -= $productRequest->quantity_requested; // ← RESTAR LA CANTIDAD
        $product->save();
    }

    // Actualizar estado
    $productRequest->update([
        'status' => 'aprobado',
        'processed_by' => Auth::id(),
        'processed_at' => now()
    ]);

    return redirect()->back()->with('success', 'Solicitud aprobada y stock actualizado.');
}

   public function reject($id)
{
    $productRequest = ProductRequest::findOrFail($id);
    
    $productRequest->update([
        'status' => 'rechazado', // ← CAMBIAR A MASCULINO
        'processed_by' => Auth::id(),
        'processed_at' => now(),
        'notes' => 'Solicitud rechazada por el administrador'
    ]);

    return redirect()->route('admin.requests.index')
        ->with('success', 'Solicitud rechazada.');
}

  public function createProductFromRequest($id)
{
    $productRequest = ProductRequest::findOrFail($id);
    
    if (!$productRequest->is_new_product) {
        return redirect()->back()->with('error', 'Esta no es una solicitud de producto nuevo');
    }

    // ✅ VERIFICAR si el producto YA EXISTE
    $existingProduct = Product::where('name', $productRequest->new_product_name)->first();

    if ($existingProduct) {
        // Si ya existe, usar ese producto
        $product = $existingProduct;
    } else {
        // Si no existe, crear uno NUEVO
        $product = Product::create([
            'name' => $productRequest->new_product_name,
            'description' => $productRequest->new_product_description,
            'price' => 0,
            'stock' => 0,
            'sku' => 'NP-' . time()
        ]);
    }

    // ✅ Actualizar la solicitud con el producto
    $productRequest->update([
        'product_id' => $product->id,
        'status' => 'producto_creado'
    ]);

    return redirect()->back()->with('success', 'Producto creado/existente vinculado exitosamente.');
}
public function markAsReview($id)
{
    $productRequest = ProductRequest::findOrFail($id);
    
    $productRequest->update([
        'status' => 'en_revision'
    ]);

    return redirect()->back()->with('success', 'Solicitud marcada como en revisión.');
}
}