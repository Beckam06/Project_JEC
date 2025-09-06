@extends('layouts.app')

@section('title', 'Solicitudes de Productos')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Solicitudes de Productos</h1>
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
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Solicitante</th>
                        <th>Receptor</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->product->name }}</td>
                        <td>{{ $request->quantity_requested }}</td>
                        <td>{{ $request->requester_name }}</td>
                        <td>{{ $request->receptor }}</td>
                        <td>
                            <span class="badge bg-{{ $request->status == 'aprobada' ? 'success' : ($request->status == 'pendiente' ? 'warning' : 'danger') }}">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($request->status == 'pendiente')
                            <form action="{{ route('admin.requests.approve', $request->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Aprobar esta solicitud?')">
                                    <i class="bi bi-check-circle"></i> Aprobar
                                </button>
                            </form>
                            <form action="{{ route('admin.requests.reject', $request->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Rechazar esta solicitud?')">
                                    <i class="bi bi-x-circle"></i> Rechazar
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
        {{ $requests->links() }}
        @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay solicitudes registradas.
        </div>
        @endif
    </div>
</div>
@endsection