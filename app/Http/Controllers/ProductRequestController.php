<?php

namespace App\Http\Controllers;

use App\Models\ProductRequest; // ← IMPORTANTE: Agregar esto
use App\Models\Product;        // ← IMPORTANTE: Agregar esto  
use App\Models\InventoryMovement; // ← IMPORTANTE: Agregar esto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    
    // Para productos nuevos, verificar que ya fueron creados
    if ($productRequest->is_new_product && !$productRequest->product_id) {
        return redirect()->route('admin.requests.index')
            ->with('error', 'Primero debe crear el producto desde la solicitud.');
    }
    
    $product = $productRequest->product;

    if ($product->stock < $productRequest->quantity_requested) {
        return redirect()->route('admin.requests.index')
            ->with('error', 'No se puede aprobar: Stock insuficiente. Disponible: ' . $product->stock);
    }

    InventoryMovement::create([
        'product_id' => $productRequest->product_id,
        'quantity' => $productRequest->quantity_requested,
        'type' => 'salida',
        'notes' => 'Solicitud aprobada: ' . $productRequest->purpose . ' - Solicitante: ' . $productRequest->requester_name,
        'receptor' => $productRequest->receptor
    ]);

    $product->stock -= $productRequest->quantity_requested;
    $product->save();

    $productRequest->update([
        'status' => 'aprobada',
        'processed_by' => Auth::id(),
        'processed_at' => now()
    ]);

    return redirect()->route('admin.requests.index')
        ->with('success', 'Solicitud aprobada y stock actualizado.');
}

    public function reject($id)
    {
        $productRequest = ProductRequest::findOrFail($id);
        
        $productRequest->update([
            'status' => 'rechazada',
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

    // Crear el nuevo producto (sin precio)
    $product = Product::create([
        'name' => $productRequest->new_product_name,
        'description' => $productRequest->new_product_description, // ← Cambiado: usar $productRequest
        'price' => 0, // Precio en 0 ya que no se maneja
        'stock' => 0, // Stock inicial en 0
        'sku' => 'NP-' . time() // SKU automático
    ]);

    // Actualizar la solicitud con el ID del producto creado
    $productRequest->update([
        'product_id' => $product->id,
        'status' => 'producto_creado'
    ]);

    return redirect()->back()->with('success', 'Producto creado exitosamente. Ahora puede aprobar la solicitud de stock.');
}
}