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
                    @php
                        $startNumber = ($requests->currentPage() - 1) * $requests->perPage() + 1;
                    @endphp
                    @foreach($requests as $index => $request)
                    <tr>
                        <td>{{ $startNumber + $index }}</td>
                        <td>
                            @if($request->product)
                                {{ $request->product->name }}
                            @else
                                Nuevo: {{ $request->new_product_name }}
                            @endif
                        </td>
                        <td>
    {{ $request->quantity_requested }}
    
    {{-- ✅ MOSTRAR SOLO SI HAY PENDIENTE --}}
    @if($request->quantity_pending && $request->quantity_pending > 0)
        <br><small class="text-warning">Pendiente: {{ $request->quantity_pending }}</small>
    @endif
</td>
                        <td>{{ $request->requester_name }}</td>
                        <td>{{ $request->receptor }}</td>
                        <td>
                            <span class="badge bg-{{ match(strtolower($request->status)) {
                                'pendiente' => 'warning',
                                'en_revision' => 'info',
                                'aprobado' => 'success',
                                'parcialmente_aprobado' => 'primary', // ← ESTADO NUEVO
                                'completado' => 'success',
                                'producto_creado' => 'primary',
                                'rechazado' => 'danger',
                                default => 'secondary'
                            } }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                        <td>
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
                                            <i class="bi bi-plus-circle"></i> Crear Producto
                                        </button>
                                    </form>
                                @endif

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

                            @elseif(strtolower($request->status) == 'parcialmente_aprobado' && $request->quantity_pending > 0)
                                <!-- ✅ BOTÓN NUEVO PARA COMPLETAR SOLICITUDES PENDIENTES -->
                                <form action="{{ route('admin.requests.complete', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" 
                                            onclick="return confirm('¿Completar las {{ $request->quantity_pending }} unidades pendientes?')">
                                        <i class="bi bi-check-all"></i> Completar ({{ $request->quantity_pending }})
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