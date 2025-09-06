@extends('layouts.app')

@section('title', 'Ver Producto')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detalles del Producto</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary me-2">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Información del Producto</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>SKU:</th>
                        <td>{{ $product->sku }}</td>
                    </tr>
                    <tr>
                        <th>Precio:</th>
                        <td>${{ number_format($product->price, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Stock:</th>
                        <td>
                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                {{ $product->stock }} unidades
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Descripción</h5>
                <p>{{ $product->description ?? 'Sin descripción' }}</p>
                
                <h5 class="mt-4">Acciones Rápidas</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('movements.create', ['type' => 'entrada', 'product_id' => $product->id]) }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Añadir Stock
                    </a>
                    <a href="{{ route('movements.create', ['type' => 'salida', 'product_id' => $product->id]) }}" class="btn btn-danger">
                        <i class="bi bi-dash-circle"></i> Retirar Stock
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection