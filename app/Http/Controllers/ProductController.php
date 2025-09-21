<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
   public function index(Request $request)
{
    $query = Product::query();
    
    // ✅ FILTRO por stock bajo
    if ($request->has('stock') && $request->stock == 'low') {
        $query->where('stock', '<', 5);
    }
    
    // ✅ BÚSQUEDA por nombre
    if ($request->has('search') && !empty($request->search)) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    
    $products = $query->latest()->paginate(10);
    
    // ✅ CORREGIDO: Mantener parámetros de búsqueda en la paginación
    if ($request->hasAny(['stock', 'search'])) {
        $products->appends($request->all());
    }
    
    return view('products.index', compact('products'));
}

    public function create()
    {
        $sku = $this->generateUniqueSku();
        return view('products.create', compact('sku'));
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:products,sku'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:products,sku,' . $product->id
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
     private function generateUniqueSku()
    {
        do {
            // Generar 5 dígitos aleatorios
            $sku = strtoupper(Str::random(5));
            
            // Verificar si ya existe
            $exists = Product::where('sku', $sku)->exists();
        } while ($exists);
        
        return $sku;
    }
}