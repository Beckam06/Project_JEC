<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Solicitudes - Sistema de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card glass-card">
                    <div class="card-header text-center bg-primary text-white">
                        <h2 class="mb-0"><i class="bi bi-list-check"></i> Historial de Solicitudes</h2>
                    </div>
                    <div class="card-body">
                        @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Casa</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                    <tr>
                                        <td>
                                            {{-- ✅ SOLUCIÓN: Verificar si el producto existe --}}
                                            @if($request->product)
                                                {{ $request->product->name }}
                                            @else
                                                {{ $request->new_product_name ?? 'Producto solicitado' }}
                                                <br>
                                                <small class="text-warning">
                                                    <i class="bi bi-clock"></i> En proceso de aprobación
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $request->quantity_requested }}</td>
                                        <td>{{ $request->receptor }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status == 'aprobada' ? 'success' : ($request->status == 'pendiente' ? 'warning' : 'danger') }}">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 4rem; color: #6c757d;"></i>
                            <h4 class="mt-3">No hay solicitudes</h4>
                            <p class="text-muted">Aún no se han realizado solicitudes</p>
                            <a href="{{ route('client.requests.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Crear primera solicitud
                            </a>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('client.requests.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Volver al formulario
                            </a>
                            
                            @if($requests->count() > 0)
                            <span class="text-muted">
                                Mostrando {{ $requests->count() }} solicitudes
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>