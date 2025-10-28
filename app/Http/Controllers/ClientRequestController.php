<?php

namespace App\Http\Controllers;

use App\Models\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientRequestController extends Controller
{
    
    public function create()
    {
        $products = Product::all();
        $casas = ['Casa Amarilla', 'Casa Naranja', 'Casa Verde', 'Estimulacion','Clinica', 'Mantenimiento','Cocina', 'Carpinteria', 'Administracion'];
        
        return view('client.requests.create', compact('products', 'casas'));
    }

    public function store(Request $request)
    {
        // Verificar si hay productos múltiples
        $hasMultipleProducts = $request->has('multiple_products') && !empty($request->multiple_products);
        
        // Si es producto nuevo
        if ($request->has('is_new_product') && $request->is_new_product) {
            $request->validate([
                'new_product_name' => 'required|string|max:255',
                'new_product_description' => 'required|string',
                'new_product_quantity' => 'required|integer|min:1',
                'receptor' => 'required|in:Casa Amarilla,Casa Naranja,Casa Verde,Estimulacion,Clinica,Mantenimiento,Cocina,Carpinteria,Administracion',
                'requester_name' => 'required|string|max:255',
                'purpose' => 'required|string|max:500'
            ]);

            ProductRequest::create([
                'product_id' => null,
                'quantity_requested' => $request->new_product_quantity,
                'receptor' => $request->receptor,
                'requester_name' => $request->requester_name,
                'purpose' => $request->purpose,
                'is_new_product' => true,
                'new_product_name' => $request->new_product_name,
                'new_product_description' => $request->new_product_description,
                'status' => 'pendiente'
            ]);

            return redirect()->route('client.requests.create')
                ->with('success', 'Solicitud de nuevo producto enviada. Será evaluada por el administrador.');
        }

        // Validación común para todos los casos
        $validated = $request->validate([
            'receptor' => 'required|in:Casa Amarilla,Casa Naranja,Casa Verde,Estimulacion,Clinica,Mantenimiento,Cocina,Carpinteria,Administracion',
            'requester_name' => 'required|string|max:255',
            'purpose' => 'required|string|max:500'
        ]);

        $totalSolicitudes = 0;

        // Procesar productos múltiples
        if ($hasMultipleProducts) {
            $multipleProducts = [];
            foreach ($request->multiple_products as $productData) {
                if (!empty($productData['product_id']) && !empty($productData['quantity'])) {
                    $multipleProducts[] = [
                        'product_id' => $productData['product_id'],
                        'quantity_requested' => $productData['quantity'],
                        'requester_name' => $validated['requester_name'],
                        'receptor' => $validated['receptor'],
                        'purpose' => $validated['purpose'],
                        'status' => 'pendiente',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Crear solicitudes para productos múltiples
            if (!empty($multipleProducts)) {
                ProductRequest::insert($multipleProducts);
                $totalSolicitudes += count($multipleProducts);
            }
        }

        // Crear solicitud individual (si existe)
        $hasIndividualProduct = $request->filled('product_id') && $request->filled('quantity_requested');
        
        if ($hasIndividualProduct) {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity_requested' => 'required|integer|min:1',
            ]);

            ProductRequest::create([
                'product_id' => $request->product_id,
                'quantity_requested' => $request->quantity_requested,
                'requester_name' => $validated['requester_name'],
                'receptor' => $validated['receptor'],
                'purpose' => $validated['purpose'],
                'status' => 'pendiente',
            ]);
            
            $totalSolicitudes += 1;
        }

        // Redireccionar con mensaje apropiado
        if ($totalSolicitudes > 0) {
            return redirect()->route('client.requests.create')
                ->with('success', "¡Solicitud enviada correctamente! Se crearon {$totalSolicitudes} solicitudes.");
        } else {
            return redirect()->route('client.requests.create')
                ->with('error', 'No se pudo procesar la solicitud. Por favor, verifica los datos.');
        }
    }

public function index(Request $request)
{
    // Obtener la casa de los parámetros de la URL
    $selectedHouse = $request->query('house');
    
    // Si no hay casa en la URL, mostrar vista vacía
    if (!$selectedHouse) {
        $requests = collect(); // Colección vacía
    } else {
        // Filtrar SOLO por la casa seleccionada
        $requests = ProductRequest::with('product')
            ->where('receptor', $selectedHouse)
            ->latest()
            ->paginate(10);
    }

    return view('client.requests.index', compact('requests', 'selectedHouse'));
}
}