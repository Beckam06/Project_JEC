@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        Productos 
        @if(request('stock') == 'low')
        <span class="badge bg-warning">Stock Bajo</span>
        @endif
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        {{-- Botón para alternar filtro --}}
        @if(request('stock') == 'low')
        <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-x-circle"></i> Limpiar Filtro
        </a>
        @else
        <a href="{{ route('products.index') }}?stock=low" class="btn btn-warning me-2">
            <i class="bi bi-exclamation-triangle"></i> Ver Stock Bajo
        </a>
        @endif
        
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </a>
    </div>
</div>

{{-- Indicador de filtro activo --}}
@if(request('stock') == 'low')
<div class="alert alert-warning">
    <i class="bi bi-filter"></i> 
    Mostrando <strong>{{ $products->total() }}</strong> productos con stock bajo (menos de 5 unidades)
</div>
@endif

<div class="card">
    <div class="card-body">
        @if($products->count() > 0) {{-- ✅ CORREGIDO: $products --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $startNumber = ($products->currentPage() - 1) * $products->perPage() + 1;
                    @endphp
                    @foreach($products as $index => $product) {{-- ✅ CORREGIDO: $products --}}
                    <tr>
                        <td>{{ $startNumber  + $index }}</td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $product->sku }}</span>
                        </td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $product->stock }} unidades
                            </span>
                        </td>
                        <td>
                            @if($product->stock == 0)
                            <span class="badge bg-danger">Agotado</span>
                            @elseif($product->stock < 5)
                            <span class="badge bg-warning text-dark">Bajo Stock</span>
                            @else
                            <span class="badge bg-success">Disponible</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('movements.create', ['product_id' => $product->id, 'type' => 'entrada']) }}" 
                                   class="btn btn-sm btn-success" title="Añadir Stock">
                                    <i class="bi bi-plus-circle"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('¿Eliminar este producto?')" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Paginación --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                @if(request('stock') == 'low')
                <span class="text-muted">
                    Mostrando {{ $products->count() }} de {{ $products->total() }} productos con stock bajo
                </span>
                @else
                <span class="text-muted">
                    Mostrando {{ $products->count() }} de {{ $products->total() }} productos
                </span>
                @endif
            </div>
            @include('components.pagination', ['paginator' => $products])
        </div>
        
        @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            @if(request('stock') == 'low')
            No hay productos con stock bajo.
            @else
            No hay productos registrados.
            @endif
            <a href="{{ route('products.create') }}" class="alert-link">
                Crear primer producto
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.badge.bg-warning.text-dark {
    color: #000 !important;
}
.btn-group .btn {
    padding: 0.25rem 0.5rem;
}
.toast {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endsection