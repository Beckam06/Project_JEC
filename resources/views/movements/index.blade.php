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

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('movements.index') }}" method="GET" id="filterForm">
            <div class="row">
                <!-- Filtro por tipo con botones de toggle -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipo de movimiento</label>
                    <div class="btn-group w-100" role="group" aria-label="Filtro por tipo">
                        <input type="radio" class="btn-check" name="type" id="type_all" value="" 
                            {{ request('type') == '' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-secondary" for="type_all">Todos</label>

                        <input type="radio" class="btn-check" name="type" id="type_entrada" value="entrada" 
                            {{ request('type') == 'entrada' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="type_entrada">Entradas</label>

                        <input type="radio" class="btn-check" name="type" id="type_salida" value="salida" 
                            {{ request('type') == 'salida' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-danger" for="type_salida">Salidas</label>
                    </div>
                </div>

                <!-- Filtro por fecha desde -->
                <div class="col-md-3 mb-3">
                    <label for="start_date" class="form-label">Desde</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="{{ request('start_date') }}">
                </div>

                <!-- Filtro por fecha hasta -->
                <div class="col-md-3 mb-3">
                    <label for="end_date" class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                        value="{{ request('end_date') }}">
                </div>

                <!-- Botones -->
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                    <a href="{{ route('movements.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($movements->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Tipo</th>
                        <th>Receptor</th>
                        <th>Notas</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $startNumber = ($movements->currentPage() - 1) * $movements->perPage() + 1;
                    @endphp
                    @foreach($movements as $index => $movement)
                    <tr>
                        <td>{{ $startNumber  + $index }}</td>
                        <td>{{ $movement->product->name }}</td>
                        <td>{{ $movement->quantity }}</td>
                        <td>
                            <span class="badge bg-{{ $movement->type == 'entrada' ? 'success' : 'danger' }}">
                                {{ $movement->type }}
                            </span>
                        </td>
                        <td>{{ $movement->receptor }}</td>
                        <td>{{ $movement->notes ?? 'Sin notas' }}</td>
                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- USANDO TU COMPONENTE UNIFICADO DE PAGINACIÓN --}}
       @include('components.pagination', ['paginator' => $movements])
        
        @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            @if(request()->has('type') || request()->has('start_date') || request()->has('end_date'))
                No se encontraron movimientos con los filtros aplicados.
            @else
                No hay movimientos registrados.
                <a href="{{ route('movements.create') }}" class="alert-link">Registrar primer movimiento</a>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
.btn-group .btn {
    flex: 1;
}

.btn-check:checked + .btn {
    font-weight: bold;
}

.btn-check:checked + .btn-outline-success {
    background-color: #198754;
    color: white;
    border-color: #198754;
}

.btn-check:checked + .btn-outline-danger {
    background-color: #dc3545;
    color: white;
    border-color: #dc3545;
}

.btn-check:checked + .btn-outline-secondary {
    background-color: #6c757d;
    color: white;
    border-color: #6c757d;
}
</style>

<script>
// Para que los botones de tipo envíen el formulario automáticamente al hacer clic
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>
@endsection