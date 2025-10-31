@extends('layouts.app')

@section('title', 'Solicitudes de Productos')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        Solicitudes de Productos
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver al Dashboard
        </a>
    </div>
</div>

 @if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif 

<!-- Tarjeta con nuevo diseño -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        @if($requests->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Producto</th>
                        <th width="100">Cantidad</th>
                        <th>Solicitante</th>
                        <th>Receptor</th>
                        <th width="120">Estado</th>
                        <th width="120">Fecha</th>
                        <th width="200">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $startNumber = ($requests->currentPage() - 1) * $requests->perPage() + 1;
                    @endphp
                    @foreach($requests as $index => $request)
                    <tr>
                        <td class="text-muted">{{ $startNumber + $index }}</td>
                        <td>
                            @if($request->product)
                                <span class="fw-medium">{{ $request->product->name }}</span>
                            @else
                                <span class="text-info">Nuevo: {{ $request->new_product_name }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-dark rounded-pill">{{ $request->quantity_requested }}</span>
                            @if($request->quantity_pending && $request->quantity_pending > 0)
                                <br><small class="text-warning">Pendiente: {{ $request->quantity_pending }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-medium">{{ $request->requester_name }}</span>
                        </td>
                        <td>{{ $request->receptor }}</td>
                        <td>
                            <span class="badge bg-{{ match(strtolower($request->status)) {
                                'pendiente' => 'warning',
                                'en_revision' => 'info',
                                'aprobado' => 'success',
                                'parcialmente_aprobado' => 'primary',
                                'completado' => 'success',
                                'producto_creado' => 'primary',
                                'rechazado' => 'danger',
                                default => 'secondary'
                            } }} rounded-pill">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                @if($request->status == 'pendiente')
                                    <form action="{{ route('admin.requests.review', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Revisar
                                        </button>
                                    </form>

                                @elseif($request->status == 'en_revision')
                                    @if($request->is_new_product && !$request->product_id)
                                        <form action="{{ route('admin.requests.create-product', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('¿Crear este producto en el inventario?')">
                                                <i class="bi bi-plus-circle"></i> Crear
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.requests.approve', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Aprobar esta solicitud?')">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.requests.reject', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Rechazar esta solicitud?')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>

                                @elseif(strtolower($request->status) == 'parcialmente_aprobado' && $request->quantity_pending > 0)
                                    <form action="{{ route('admin.requests.complete', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" 
                                                onclick="return confirm('¿Completar las {{ $request->quantity_pending }} unidades pendientes?')">
                                            <i class="bi bi-check-all"></i> Completar
                                        </button>
                                    </form>

                                @elseif($request->status == 'producto_creado')
                                    <form action="{{ route('admin.requests.approve', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Aprobar esta solicitud?')">
                                            <i class="bi bi-check-circle"></i> Aprobar
                                        </button>
                                    </form>

                                @else
                                    <span class="text-muted small">Procesada</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
      {{-- Paginación --}}
@include('components.pagination', ['paginator' => $requests])
        
        @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
            </div>
            <h5 class="text-muted">No hay solicitudes registradas</h5>
            <p class="text-muted mb-4">No se han encontrado solicitudes de productos en el sistema</p>
        </div>
        @endif
    </div>
</div>

<style>
.card {
    border-radius: 0.5rem;
}

.btn {
    border-radius: 0.375rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.03);
}

.badge.rounded-pill {
    border-radius: 50rem;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endsection