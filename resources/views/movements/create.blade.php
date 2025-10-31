@extends('layouts.app')

@section('title', 'Registrar Movimiento')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        @if(isset($product) && $product)
            Añadir Stock - {{ $product->name }}
        @else
            Registrar Movimiento de Inventario
        @endif
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('movements.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('movements.store') }}" method="POST" id="movementForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Producto *</label>
                        
                        @if(isset($product) && $product)
                            {{-- MODO "AÑADIR STOCK" - Producto precargado --}}
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small>Stock actual: {{ $product->stock }} unidades</small>
                                    </div>
                                    <span class="badge bg-success">ENTRADA</span>
                                </div>
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="type" value="entrada">
                            </div>
                        @else
                            {{-- MODO "MOVIMIENTO COMPLETO" --}}
                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                <option value="">Seleccionar producto</option>
                                @foreach($products as $p)
                                <option value="{{ $p->id }}" 
                                    {{ (old('product_id') == $p->id) ? 'selected' : '' }} 
                                    data-stock="{{ $p->stock }}">
                                    {{ $p->name }} (Stock: {{ $p->stock }})
                                </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo de Movimiento *</label>
                        
                        @if(isset($product) && $product)
                            {{-- MODO "AÑADIR STOCK" - Tipo fijo --}}
                            <div class="alert alert-success">
                                <i class="bi bi-box-arrow-in-down"></i> Entrada (Añadiendo stock)
                            </div>
                        @else
                            {{-- MODO "MOVIMIENTO COMPLETO" --}}
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="entrada" {{ (old('type') == 'entrada') ? 'selected' : '' }}>Entrada (+)</option>
                                <option value="salida" {{ (old('type') == 'salida') ? 'selected' : '' }}>Salida (-)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad *</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                            id="quantity" name="quantity" value="{{ old('quantity') }}" 
                            min="1" required>
                        <div class="form-text" id="stockInfo">
                            @if(isset($product) && $product)
                                <span class="text-info">
                                    <i class="bi bi-info-circle"></i> 
                                    Stock actual: {{ $product->stock }} unidades
                                </span>
                            @endif
                        </div>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
           
                <!-- CAMPO RECEPTOR - SOLO PARA SALIDAS EN MODO COMPLETO -->
                <div class="col-md-6">
                    <div class="mb-3" id="receptorField" style="display: none;">
                        <label for="receptor" class="form-label">Receptor *</label>
                        <select class="form-select @error('receptor') is-invalid @enderror" id="receptor" name="receptor">
                            <option value="">Seleccionar receptor</option>
                            <optgroup label="Casas">
                                <option value="Casa Amarilla" {{ old('receptor') == 'Casa Amarilla' ? 'selected' : '' }}>Casa Amarilla</option>
                                <option value="Casa Naranja" {{ old('receptor') == 'Casa Naranja' ? 'selected' : '' }}>Casa Naranja</option>
                                <option value="Casa Verde" {{ old('receptor') == 'Casa Verde' ? 'selected' : '' }}>Casa Verde</option>
                            </optgroup>
                            <optgroup label="Áreas">
                                <option value="Estimulacion" {{ old('receptor') == 'Estimulacion' ? 'selected' : '' }}>Estimulacion</option>
                                <option value="Clinica" {{ old('receptor') == 'Clinica' ? 'selected' : '' }}>Clinica</option>
                                <option value="Mantenimiento" {{ old('receptor') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                <option value="Cocina" {{ old('receptor') == 'Cocina' ? 'selected' : '' }}>Cocina</option>
                                <option value="Carpinteria" {{ old('receptor') == 'Carpinteria' ? 'selected' : '' }}>Carpinteria</option>
                                <option value="Administracion" {{ old('receptor') == 'Administracion' ? 'selected' : '' }}>Administracion</option>
                            </optgroup>
                        </select>
                        @error('receptor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notas</label>
                <input type="text" class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" 
                    value="{{ old('notes') }}" placeholder="Ej: Compra mensual, Reposición de stock, etc.">
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('movements.index') }}" class="btn btn-secondary me-md-2">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> 
                    @if(isset($product) && $product)
                        Añadir Stock
                    @else
                        Registrar Movimiento
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isPreloaded = {{ isset($product) && $product ? 'true' : 'false' }};
    
    // Solo inicializar JavaScript si NO viene precargado
    if (!isPreloaded) {
        const typeSelect = document.getElementById('type');
        const receptorField = document.getElementById('receptorField');
        const receptorSelect = document.getElementById('receptor');
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const stockInfo = document.getElementById('stockInfo');
        const form = document.getElementById('movementForm');

        function toggleReceptorField() {
            if (typeSelect.value === 'salida') {
                receptorField.style.display = 'block';
                receptorSelect.setAttribute('required', 'required');
                updateStockInfo();
            } else {
                receptorField.style.display = 'none';
                receptorSelect.removeAttribute('required');
                receptorSelect.value = '';
            }
        }

        function updateStockInfo() {
            if (!productSelect.value) {
                stockInfo.innerHTML = '';
                return;
            }

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const stock = parseInt(selectedOption.getAttribute('data-stock'));
            const quantity = parseInt(quantityInput.value) || 0;

            if (typeSelect.value === 'salida') {
                if (quantity > stock) {
                    stockInfo.innerHTML = `<span class="text-danger">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Stock insuficiente. Máximo: ${stock} unidades
                    </span>`;
                } else {
                    stockInfo.innerHTML = `<span class="text-success">
                        <i class="bi bi-check-circle"></i> 
                        Stock disponible: ${stock} unidades
                    </span>`;
                }
            } else {
                stockInfo.innerHTML = `<span class="text-info">
                    <i class="bi bi-info-circle"></i> 
                    Stock actual: ${stock} unidades
                </span>`;
            }
        }

        function validateStock() {
            if (typeSelect.value === 'salida' && productSelect.value) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const stock = parseInt(selectedOption.getAttribute('data-stock'));
                const quantity = parseInt(quantityInput.value);
                
                if (quantity > stock) {
                    alert(`❌ Stock insuficiente\n\nSolo hay ${stock} unidades disponibles\nCantidad solicitada: ${quantity}`);
                    quantityInput.focus();
                    return false;
                }
            }
            return true;
        }

        // Event listeners
        typeSelect.addEventListener('change', function() {
            toggleReceptorField();
            updateStockInfo();
        });

        productSelect.addEventListener('change', updateStockInfo);
        quantityInput.addEventListener('input', updateStockInfo);

        form.addEventListener('submit', function(e) {
            if (typeSelect.value === 'salida' && !receptorSelect.value) {
                e.preventDefault();
                alert('Por favor seleccione un receptor para la salida');
                receptorSelect.focus();
                return;
            }
            
            if (!validateStock()) {
                e.preventDefault();
                return;
            }
        });

        // Inicializar
        toggleReceptorField();
        updateStockInfo();
    }
});
</script>
@endsection