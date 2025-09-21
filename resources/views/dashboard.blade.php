@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<!-- Tarjetas de estadísticas principales -->
<div class="row mb-4">
    <!-- Productos -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75">Total de</h6>
                        <h3 class="card-title fw-bold mb-0">{{ $totalProducts }}</h3>
                        <span class="card-text">Productos</span>
                    </div>
                    <div class="icon-container">
                        <i class="bi bi-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Entradas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75">Total de</h6>
                        <h3 class="card-title fw-bold mb-0">{{ $totalEntries }}</h3>
                        <span class="card-text">Entradas</span>
                    </div>
                    <div class="icon-container">
                        <i class="bi bi-arrow-down-left"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Salidas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75">Total de</h6>
                        <h3 class="card-title fw-bold mb-0">{{ $totalOutputs }}</h3>
                        <span class="card-text">Salidas</span>
                    </div>
                    <div class="icon-container">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Bajo -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75">Productos con</h6>
                        <h3 class="card-title fw-bold mb-0">{{ $lowStockCount }}</h3>
                        <span class="card-text">Stock Bajo</span>
                    </div>
                    <div class="icon-container">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
                @if($lowStockCount > 0)
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('products.index') }}?stock=low" class="btn btn-sm btn-dark w-100">
                        <i class="bi bi-eye me-1"></i> Ver productos
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Segunda fila de estadísticas -->
<div class="row mb-4">
    <!-- Solicitudes Pendientes -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card dashboard-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75">Solicitudes</h6>
                        <h3 class="card-title fw-bold mb-0">{{ $pendingRequestsCount }}</h3>
                        <span class="card-text">Pendientes</span>
                    </div>
                    <div class="icon-container">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Movimientos Hoy -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card dashboard-card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75">Movimientos</h6>
                        <h3 class="card-title fw-bold mb-0">0</h3>
                        <span class="card-text">Hoy</span>
                    </div>
                    <div class="icon-container">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ratio Entradas/Salidas -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card dashboard-card bg-purple text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-1 opacity-75">Balance</h6>
                        <h3 class="card-title fw-bold mb-0">{{ $totalEntries - $totalOutputs }}</h3>
                        <span class="card-text">Neto</span>
                    </div>
                    <div class="icon-container">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimos movimientos -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-semibold mb-0 d-flex align-items-center">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Últimos movimientos
                    <a href="{{ route('movements.index') }}" class="btn btn-sm btn-outline-primary ms-auto">
                        <i class="bi bi-arrow-right"></i> Ver todos
                    </a>
                </h5>
            </div>
            <div class="card-body p-0">
                @if($recentMovements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Producto</th>
                                <th width="100">Cantidad</th>
                                <th width="100">Tipo</th>
                                <th width="120">Receptor</th>
                                <th width="140" class="pe-4">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMovements as $movement)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-medium">{{ $movement->product->name ?? 'Producto eliminado' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-dark rounded-pill px-3 py-2">{{ $movement->quantity }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $movement->type == 'entrada' ? 'success' : 'danger' }} rounded-pill px-3 py-2">
                                        {{ $movement->type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $movement->receptor ?? 'N/A' }}</span>
                                </td>
                                <td class="pe-4">
                                    <span class="text-muted small">{{ $movement->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted mb-0">No hay movimientos registrados</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Tarjetas principales del dashboard */
.dashboard-card {
    border: none;
    border-radius: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    height: 100%;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.dashboard-card .card-body {
    padding: 1.5rem;
}

.dashboard-card .card-title {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.dashboard-card .card-subtitle {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dashboard-card .card-text {
    font-size: 1.1rem;
    font-weight: 500;
}

.dashboard-card .icon-container {
    font-size: 2.5rem;
    opacity: 0.8;
}

/* Color personalizado */
.bg-purple {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%) !important;
}

/* Tabla de movimientos */
.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.04);
}

/* Badges */
.badge {
    font-size: 0.85rem;
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-card .card-title {
        font-size: 2rem;
    }
    
    .dashboard-card .icon-container {
        font-size: 2rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
}

/* Animaciones suaves */
.card {
    transition: all 0.3s ease;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Mejora visual para la tabla */
.table > :not(caption) > * > * {
    padding: 1rem 0.5rem;
}

.table tbody tr {
    border-bottom: 1px solid #f8f9fa;
}

.table tbody tr:last-child {
    border-bottom: none;
}
</style>
@endsection