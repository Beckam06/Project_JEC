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
    DB::beginTransaction();
    try {
        $productRequest = ProductRequest::findOrFail($id);
        
        // Si es producto NUEVO y NO tiene product_id → ERROR
        if ($productRequest->is_new_product && !$productRequest->product_id) {
            return redirect()->back()->with('error', 'Primero debe crear el producto desde la solicitud.');
        }
        
        $product = $productRequest->product;
        $quantityRequested = $productRequest->quantity_requested;

        // ✅ PARA PRODUCTOS NUEVOS: SOLO ENTRADA INICIAL
        if ($productRequest->is_new_product) {
            
            // SOLO ENTRADA de la cantidad solicitada
            InventoryMovement::create([
                'product_id' => $product->id,
                'quantity' => $quantityRequested,
                'type' => 'entrada',
                'notes' => 'Entrada inicial para producto nuevo: ' . $product->name,
                'receptor' => $productRequest->receptor
            ]);

            $product->stock += $quantityRequested;
            $product->save();

            // ACTUALIZAR SOLICITUD CON VALORES EXPLÍCITOS
            $productRequest->quantity_approved = $quantityRequested;
            $productRequest->quantity_pending = 0;
            $productRequest->status = 'aprobado';
            $productRequest->processed_by = Auth::id();
            $productRequest->processed_at = now();
            $productRequest->notes = 'Producto nuevo aprobado completamente';
            $productRequest->save();

        } 
        // ✅ PARA PRODUCTOS EXISTENTES
        else {
            $availableStock = $product->stock;
            
            // ✅ CASO 1: STOCK SUFICIENTE - APROBAR TODO
            if ($availableStock >= $quantityRequested) {
                
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'quantity' => $quantityRequested,
                    'type' => 'salida',
                    'notes' => 'Solicitud aprobada: ' . $productRequest->purpose,
                    'receptor' => $productRequest->receptor
                ]);

                $product->stock -= $quantityRequested;
                $product->save();

                // ACTUALIZAR CON VALORES EXPLÍCITOS - SIN NULL
                $productRequest->quantity_approved = $quantityRequested;
                $productRequest->quantity_pending = 0;
                $productRequest->status = 'aprobado';
                $productRequest->processed_by = Auth::id();
                $productRequest->processed_at = now();
                $productRequest->notes = 'Solicitud aprobada completamente';
                $productRequest->save();

            } 
            // ✅ CASO 2: STOCK INSUFICIENTE - APROBAR PARCIALMENTE
            else {
                $quantityApproved = $availableStock;
                $quantityPending = $quantityRequested - $availableStock;
                
                // A. APROBAR EL STOCK DISPONIBLE (si hay algo)
                if ($availableStock > 0) {
                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'quantity' => $availableStock,
                        'type' => 'salida',
                        'notes' => "Solicitud parcial aprobada. Entregado: $availableStock, Pendiente: $quantityPending. " . $productRequest->purpose,
                        'receptor' => $productRequest->receptor
                    ]);
                    
                    $product->stock -= $availableStock;
                    $product->save();
                }
                
                // B. ACTUALIZAR SOLICITUD CON VALORES EXPLÍCITOS - ¡SIN USAR UPDATE()!
                $productRequest->quantity_approved = $availableStock;
                $productRequest->quantity_pending = $quantityPending;
                $productRequest->status = 'parcialmente_aprobado';
                $productRequest->notes = "Solicitud parcial. Entregado: $availableStock, Faltante: $quantityPending";
                $productRequest->processed_by = Auth::id();
                $productRequest->processed_at = now();
                $productRequest->save();

                DB::commit();
                
                return redirect()->back()->with('warning', 
                    "Solicitud parcialmente aprobada. Se entregaron $availableStock unidades. " .
                    "Quedan $quantityPending unidades pendientes."
                );
            }
        }

        // Confirmar transacción
        DB::commit();
        
        return redirect()->back()->with('success', 'Solicitud aprobada y stock actualizado.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al aprobar la solicitud.');
    }
}
    public function completePending($id)
{
    $productRequest = ProductRequest::findOrFail($id);
    
    if ($productRequest->status !== 'parcialmente_aprobado' || !$productRequest->quantity_pending) {
        return redirect()->back()->with('error', 'No hay cantidad pendiente para completar');
    }
    
    $product = $productRequest->product;
    $pendingQuantity = $productRequest->quantity_pending;
    
    // Verificar si ahora hay stock suficiente
    if ($product->stock >= $pendingQuantity) {
        InventoryMovement::create([
            'product_id' => $product->id,
            'quantity' => $pendingQuantity,
            'type' => 'salida',
            'notes' => "Completación de solicitud pendiente #{$productRequest->id}",
            'receptor' => $productRequest->receptor
        ]);
        
        $product->stock -= $pendingQuantity;
        $product->save();
        
        $productRequest->update([
            'status' => 'completado',
            'quantity_pending' => 0,
            'notes' => "Solicitud completada. Todas las unidades entregadas."
        ]);
        
        return redirect()->back()->with('success', "Solicitud completada. Se entregaron $pendingQuantity unidades pendientes.");
    } else {
        return redirect()->back()->with('error', 
            "Aún no hay stock suficiente. Necesitas $pendingQuantity unidades, pero solo hay {$product->stock}."
        );
    }
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