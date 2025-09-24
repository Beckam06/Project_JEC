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
    // WHY: Primero verificamos si es producto nuevo
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

    // WHY: Si NO es producto nuevo
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity_requested' => 'required|integer|min:1',
        'receptor' => 'required|in:Casa Amarilla,Casa Naranja,Casa Verde,Estimulacion,Clinica,Mantenimiento,Cocina,Carpinteria,Administracion',
        'requester_name' => 'required|string|max:255',
        'purpose' => 'required|string|max:500'
    ]);

    // ✅✅✅ CREAR SOLICITUD DIRECTAMENTE SIN VERIFICAR STOCK
    ProductRequest::create([
        'product_id' => $request->product_id,
        'quantity_requested' => $request->quantity_requested,
        'receptor' => $request->receptor,
        'requester_name' => $request->requester_name,
        'purpose' => $request->purpose,
        'status' => 'pendiente'
    ]);

    return redirect()->route('client.requests.create')
        ->with('success', 'Solicitud enviada exitosamente. Será procesada pronto.');
}
    public function index()
    {
        $requests = ProductRequest::with('product')->latest()->paginate(10);
        return view('client.requests.index', compact('requests'));
    }
}