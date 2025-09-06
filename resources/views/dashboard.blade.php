@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card card-dashboard bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Productos</h5>
                        <h2 class="card-text">{{ $totalProducts }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-box fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-dashboard bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Entradas</h5>
                        <h2 class="card-text">{{ $totalEntries }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-arrow-down-left fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-dashboard bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Salidas</h5>
                        <h2 class="card-text">{{ $totalOutputs }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-arrow-up-right fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card card-dashboard text-white" style="background-color: #B0AA92;">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">Solicitudes Pendientes</h5>
                    <h2 class="card-text">{{ $pendingRequestsCount }}</h2>
                </div>
                <div>
                    <i class="bi bi-clipboard-check fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resto del dashboard (movimientos recientes y stock bajo) -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Últimos movimientos</h5>
            </div>
            <div class="card-body">
                @if($recentMovements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Tipo</th>
                                <th>Receptor</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMovements as $movement)
                            <tr>
                                <td>{{ $movement->product->name ?? 'Producto eliminado' }}</td>
                                <td>{{ $movement->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $movement->type == 'entrada' ? 'success' : 'danger' }}">
                                        {{ $movement->type }}
                                    </span>
                                </td>
                                <td>{{ $movement->receptor ?? 'N/A' }}</td>
                                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay movimientos registrados.
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Productos con stock bajo</h5>
            </div>
            <div class="card-body">
                <!-- ... tabla de stock bajo ... -->
            </div>
        </div>
    </div>
</div>
@endsection