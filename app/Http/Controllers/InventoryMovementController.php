<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductRequest;

use Dompdf\Dompdf;
use Dompdf\Options;

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
    if ($request->has('receptor') && $request->receptor != '') {
    $query->where('receptor', 'like', '%' . $request->receptor . '%');
    }
    
    $movements = $query->paginate(10);
    
    // Mantener los parámetros de filtro en la paginación
    if ($request->anyFilled(['type', 'start_date', 'end_date'])) {
        $movements->appends($request->all());
    }
    
    return view('movements.index', compact('movements'));
}

 public function create(Request $request)
{
    $product = null;
    $type = $request->get('type', 'entrada');
    
    // Si se pasa un producto ID, cargarlo (para "Añadir Stock")
    if ($request->has('product')) {
        $product = Product::find($request->get('product'));
        $type = 'entrada'; // Forzar entrada cuando viene de "Añadir Stock"
    }
    
    $products = Product::all();
    
    return view('movements.create', compact('products', 'product', 'type'));
}
   public function store(Request $request)
{
    $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'type' => 'required|in:entrada,salida',
        'notes' => 'nullable'
    ];

    // Solo hacer required el receptor si es una SALIDA
    if ($request->type == 'salida') {
        $rules['receptor'] = 'required';
        
        // ✅ AGREGAR VALIDACIÓN DE STOCK PARA SALIDAS
        $product = Product::find($request->product_id);
        if ($product) {
            $rules['quantity'] .= '|max:' . $product->stock;
        }
    }

    // Validar
    $request->validate($rules);

    // Validación adicional manual para stock
    if ($request->type == 'salida') {
        $product = Product::find($request->product_id);
        if ($product && $request->quantity > $product->stock) {
            return back()->withErrors([
                'quantity' => "Stock insuficiente. Solo hay {$product->stock} unidades disponibles."
            ])->withInput();
        }
    }

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
  

public function generatePDF(Request $request)
    {
        // TUS FILTROS EXISTENTES
        $query = InventoryMovement::with('product')->latest();
        
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        if ($request->has('receptor') && $request->receptor != '') {
            $query->where('receptor', 'like', '%' . $request->receptor . '%');
        }
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $movements = $query->get();
        
        // GENERAR PDF CON DOMPDF DIRECTO
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        $html = view('movements.pdf', compact('movements'))->render();
        
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        return $dompdf->stream('reporte-movimientos-' . date('Y-m-d') . '.pdf');
    }

    
}