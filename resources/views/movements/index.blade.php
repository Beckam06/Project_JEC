@extends('layouts.app')

@section('title', 'Movimientos de Inventario')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        Movimientos de Inventario
        @if(request('type') || request('start_date') || request('end_date'))
        <span class="badge bg-info fs-6">Filtrado</span>
        @endif
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
    <a href="{{ route('movements.create') }}" class="btn btn-sm btn-success me-2">
        <i class="bi bi-plus-circle me-1"></i> Nuevo Movimiento
    </a>
    <a href="{{ route('movements.pdf', request()->all()) }}" 
       class="btn btn-sm btn-outline-danger" target="_blank">
        <i class="bi bi-file-pdf me-1"></i> Ver PDF
    </a>
</div>
</div>

<!-- Filtros - SOLO DISEÑO MEJORADO -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('movements.index') }}" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">
                <!-- Filtro por tipo -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tipo de movimiento</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="type" id="type_all" value="" 
                            {{ request('type') == '' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-secondary rounded-pill-start" for="type_all">
                            <i class="bi bi-grid-1x2 me-1"></i>Todos
                        </label>

                        <input type="radio" class="btn-check" name="type" id="type_entrada" value="entrada" 
                            {{ request('type') == 'entrada' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-success" for="type_entrada">
                            <i class="bi bi-box-arrow-in-down me-1"></i>Entradas
                        </label>

                        <input type="radio" class="btn-check" name="type" id="type_salida" value="salida" 
                            {{ request('type') == 'salida' ? 'checked' : '' }} autocomplete="off">
                        <label class="btn btn-outline-danger rounded-pill-end" for="type_salida">
                            <i class="bi bi-box-arrow-up me-1"></i>Salidas
                        </label>
                    </div>
                </div>

                <!-- Filtro por fechas -->
                <div class="col-md-3">
                    <label for="start_date" class="form-label fw-semibold">Desde</label>
                    <input type="date" class="form-control rounded-pill" id="start_date" name="start_date" 
                        value="{{ request('start_date') }}">
                </div>

                <div class="col-md-3">
                    <label for="end_date" class="form-label fw-semibold">Hasta</label>
                    <input type="date" class="form-control rounded-pill" id="end_date" name="end_date" 
                        value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
    <label for="receptor" class="form-label fw-semibold">Receptor</label>
    <input type="text" class="form-control rounded-pill" id="receptor" name="receptor" 
           value="{{ request('receptor') }}" placeholder="Nombre del receptor">
</div>

                <!-- Botones -->
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('movements.index') }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de movimientos - SOLO DISEÑO MEJORADO -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        @if($movements->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Producto</th>
                        <th width="100">Cantidad</th>
                        <th width="100">Tipo</th>
                        <th>Receptor</th>
                        <th>Notas</th>
                        <th width="140">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $startNumber = ($movements->currentPage() - 1) * $movements->perPage() + 1;
                    @endphp
                    @foreach($movements as $index => $movement)
                    <tr>
                        <td class="text-muted">{{ $startNumber + $index }}</td>
                        <td>{{ $movement->product->name }}</td>
                        <td>
                            <span class="badge bg-dark rounded-pill">{{ $movement->quantity }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $movement->type == 'entrada' ? 'success' : 'danger' }} rounded-pill">
                                {{ $movement->type }}
                            </span>
                        </td>
                        <td>{{ $movement->receptor }}</td>
                        <td>
                            <span class="text-muted small">{{ $movement->notes ?? 'Sin notas' }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $movement->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="text-muted small">
                Mostrando {{ $movements->count() }} de {{ $movements->total() }} registros
            </div>
            @include('components.pagination', ['paginator' => $movements])
        </div>
        
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

/* Nuevos estilos agregados */
.card {
    border-radius: 0.5rem;
}

.btn-rounded {
    border-radius: 50rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.03);
}

.badge-rounded {
    border-radius: 50rem;
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