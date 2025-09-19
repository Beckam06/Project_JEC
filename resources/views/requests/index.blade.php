@extends('layouts.app')

@section('title', 'Gestión de Solicitudes - Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-clipboard-check"></i> Gestión de Solicitudes
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Dashboard
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

<div class="card">
    <div class="card-body">
        @if($requests->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Solicitante</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Stock Actual</th>
                        <th>Casa</th>
                        <th>Propósito</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $startNumber = ($products->currentPage() - 1) * $products->perPage() + 1;
                    @endphp
                    @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->requester_name }}</td>
                        <td>{{ $request->product->name }}</td>
                        <td>{{ $request->quantity_requested }}</td>
                        <td>
                            <span class="badge bg-{{ $request->product->stock >= $request->quantity_requested ? 'success' : 'danger' }}">
                                {{ $request->product->stock }}
                            </span>
                        </td>
                        <td>{{ $request->receptor }}</td>
                        <td>{{ Str::limit($request->purpose, 30) }}</td>
                        <td>
                            <span class="badge bg-{{ $request->status == 'aprobada' ? 'success' : ($request->status == 'pendiente' ? 'warning' : 'danger') }}">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($request->status == 'pendiente')
                            <form action="{{ route('admin.requests.approve', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" 
                                        onclick="return confirm('¿Aprobar esta solicitud de {{ $request->quantity_requested }} unidades para {{ $request->requester_name }}?')">
                                    <i class="bi bi-check"></i> Aprobar
                                </button>
                            </form>
                            <form action="{{ route('admin.requests.reject', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('¿Rechazar esta solicitud de {{ $request->requester_name }}?')">
                                    <i class="bi bi-x"></i> Rechazar
                                </button>
                            </form>
                            @else
                            <span class="text-muted">Procesada</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Mostrando {{ $requests->firstItem() }} - {{ $requests->lastItem() }} de {{ $requests->total() }} registros
            </div>
            {{ $requests->links() }}
        </div>
        @else
        <div class="alert alert-info text-center py-4">
            <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
            <h4 class="mt-3">No hay solicitudes</h4>
            <p class="text-muted">No se han encontrado solicitudes de productos.</p>
        </div>
        @endif
    </div>
</div>

<!-- Estadísticas -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h5><i class="bi bi-clock"></i> Pendientes</h5>
                <h3>{{ ProductRequest::where('status', 'pendiente')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5><i class="bi bi-check-circle"></i> Aprobadas</h5>
                <h3>{{ ProductRequest::where('status', 'aprobada')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h5><i class="bi bi-x-circle"></i> Rechazadas</h5>
                <h3>{{ ProductRequest::where('status', 'rechazada')->count() }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection