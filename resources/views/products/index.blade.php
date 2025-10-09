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
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-x-circle"></i> Limpiar Filtro
        </a>
        @else
        <a href="{{ route('products.index') }}?stock=low" class="btn btn-sm btn-outline-warning me-2">
            <i class="bi bi-exclamation-triangle"></i> Stock Bajo
        </a>
        @endif
        
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo
        </a>
    </div>
</div>

{{-- Barra de búsqueda mejorada --}}
<div class="row mb-4">
    <div class="col-md-8 col-lg-6">
        <div class="search-card card border-0 shadow-sm">
            <div class="card-body p-3">
                <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center">
                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text bg-transparent border-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light rounded-pill me-2" 
                               name="search" placeholder="Buscar productos..." value="{{ request('search') }}"
                               style="min-width: 200px;">
                        
                        {{-- Mantener otros filtros --}}
                        @if(request('stock'))
                            <input type="hidden" name="stock" value="{{ request('stock') }}">
                        @endif
                        
                        @if(request()->has('search'))
                        <a href="{{ route('products.index') }}{{ request('stock') ? '?stock=low' : '' }}" 
                           class="btn btn-sm btn-outline-secondary rounded-pill me-2" title="Limpiar búsqueda">
                            <i class="bi bi-x"></i>
                        </a>
                        @endif
                        
                        <button class="btn btn-primary btn-sm rounded-pill px-3" type="submit">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Indicador de filtro activo --}}
@if(request('stock') == 'low' || request('search'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="bi bi-funnel me-2"></i>
    @if(request('stock') == 'low' && request('search'))
        <span>Filtrado: <strong>Stock bajo</strong> y <strong>"{{ request('search') }}"</strong></span>
    @elseif(request('stock') == 'low')
        <span>Mostrando <strong>productos con stock bajo</strong> (menos de 5 unidades)</span>
    @elseif(request('search'))
        <span>Búsqueda: <strong>"{{ request('search') }}"</strong></span>
    @endif
    <span class="badge bg-dark ms-2">{{ $products->total() }} resultados</span>
    
    <a href="{{ route('products.index') }}" class="btn-close" aria-label="Close"></a>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Producto</th>
                        <th width="120">SKU</th>
                        <th width="120">Precio</th>
                        <th width="120">Stock</th>
                        <th width="120">Estado</th>
                        <th width="200" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $startNumber = ($products->currentPage() - 1) * $products->perPage() + 1;
                    @endphp
                    @foreach($products as $index => $product)
                    <tr>
                        <td class="text-muted">{{ $startNumber + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-box-seam text-muted"></i>
                                </div>
                                <div>
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark fw-medium">
                                        {{ $product->name }}
                                    </a>
                                    @if($product->description)
                                    <p class="text-muted small mb-0">{{ Str::limit($product->description, 40) }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $product->sku }}</span>
                        </td>
                        <td class="fw-semibold">L{{ number_format($product->price, 2) }}</td>
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
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('movements.create', ['product_id' => $product->id, 'type' => 'entrada']) }}" 
                                   class="btn btn-sm btn-outline-success" title="Añadir Stock">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
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
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="text-muted small">
                @if(request('stock') == 'low' && request('search'))
                Mostrando {{ $products->count() }} de {{ $products->total() }} productos con stock bajo que coinciden con "{{ request('search') }}"
                @elseif(request('stock') == 'low')
                Mostrando {{ $products->count() }} de {{ $products->total() }} productos con stock bajo
                @elseif(request('search'))
                Mostrando {{ $products->count() }} de {{ $products->total() }} productos que coinciden con "{{ request('search') }}"
                @else
                Mostrando {{ $products->count() }} de {{ $products->total() }} productos
                @endif
            </div>
            @include('components.pagination', ['paginator' => $products])
        </div>
        
        @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-search display-4 text-muted"></i>
            </div>
            <h5 class="text-muted">
                @if(request('stock') == 'low' && request('search'))
                No hay productos con stock bajo que coincidan con "{{ request('search') }}"
                @elseif(request('stock') == 'low')
                No hay productos con stock bajo
                @elseif(request('search'))
                No se encontraron productos para "{{ request('search') }}"
                @else
                No hay productos registrados
                @endif
            </h5>
            <p class="text-muted mb-4">Intenta ajustar los filtros o crear un nuevo producto</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Crear primer producto
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.search-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.input-group-text {
    padding: 0.375rem 0.75rem;
}

.form-control:focus {
    box-shadow: none;
    border-color: #86b7fe;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

.badge.bg-warning.text-dark {
    color: #000 !important;
}

.alert-dismissible .btn-close {
    padding: 0.75rem 1.25rem;
}
</style>
@endsection