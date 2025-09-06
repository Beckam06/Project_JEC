@extends('layouts.app')

@section('title', 'Movimientos de Inventario')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Movimientos de Inventario</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('movements.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Movimiento
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($movements->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Tipo</th>
                        <th>Notas</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $movement)
                    <tr>
                        <td>{{ $movement->id }}</td>
                        <td>{{ $movement->product->name }}</td>
                        <td>{{ $movement->quantity }}</td>
                        <td>
                            <span class="badge bg-{{ $movement->type == 'entrada' ? 'success' : 'danger' }}">
                                {{ $movement->type }}
                            </span>
                        </td>
                        <td>{{ $movement->notes ?? 'Sin notas' }}</td>
                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $movements->links() }}
        @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay movimientos registrados.
            <a href="{{ route('movements.create') }}" class="alert-link">Registrar primer movimiento</a>
        </div>
        @endif
    </div>
</div>
@endsection