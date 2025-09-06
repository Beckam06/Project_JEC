@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Productos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('products.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
        @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay productos registrados.
            <a href="{{ route('products.create') }}" class="alert-link">Crear primer producto</a>
        </div>
        @endif
    </div>
</div>
@endsection