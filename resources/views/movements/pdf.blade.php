<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Movimientos - {{ date('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { color: #2c5282; margin: 0; font-size: 24px; }
        .header p { color: #666; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px; }
        th { background-color: #2c5282; color: white; padding: 8px; text-align: left; font-weight: bold; }
        td { padding: 6px; border-bottom: 1px solid #ddd; }
        .badge { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .entrada { background-color: #10b981; color: white; }
        .salida { background-color: #ef4444; color: white; }
        .footer { margin-top: 20px; text-align: center; color: #666; font-size: 11px; border-top: 1px solid #ddd; padding-top: 10px; }
        .filters { background-color: #f3f4f6; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .filters span { font-weight: bold; color: #2c5282; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE MOVIMIENTOS DE INVENTARIO</h1>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
    </div>

    @if(request()->anyFilled(['type', 'receptor', 'start_date', 'end_date']))
    <div class="filters">
        <strong>Filtros aplicados:</strong><br>
        @if(request('type'))<span>Tipo:</span> {{ request('type') }} | @endif
        @if(request('receptor'))<span>Receptor:</span> {{ request('receptor') }} | @endif
        @if(request('start_date'))<span>Desde:</span> {{ request('start_date') }} | @endif
        @if(request('end_date'))<span>Hasta:</span> {{ request('end_date') }} @endif
    </div>
    @endif

    <table>
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
            @foreach($movements as $index => $movement)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $movement->product->name }}</td>
                <td>{{ $movement->quantity }}</td>
                <td>
                    <span class="badge {{ $movement->type == 'entrada' ? 'entrada' : 'salida' }}">
                        {{ $movement->type }}
                    </span>
                </td>
                <td>{{ $movement->receptor ?? 'N/A' }}</td>
                <td>{{ $movement->notes ?? 'Sin notas' }}</td>
                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($movements->count() == 0)
    <div style="text-align: center; padding: 20px; color: #666;">
        No hay movimientos con los filtros aplicados
    </div>
    @endif

    <div class="footer">
        <p>Sistema de Inventario - {{ date('Y') }}</p>
    </div>
</body>
</html>