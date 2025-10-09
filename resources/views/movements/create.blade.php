@extends('layouts.app')

@section('title', 'Registrar Movimiento')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Registrar Movimiento de Inventario</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('movements.store') }}" method="POST" id="movementForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Producto *</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                            <option value="">Seleccionar producto</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ (old('product_id') == $product->id) ? 'selected' : '' }} data-stock="{{ $product->stock }}">
                                {{ $product->name }} (Stock: {{ $product->stock }})
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo de Movimiento *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="entrada" {{ old('type') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                            <option value="salida" {{ old('type') == 'salida' ? 'selected' : '' }}>Salida</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

                    
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad *</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                            id="quantity" name="quantity" value="{{ old('quantity') }}" 
                            min="1" required>
                        <div class="form-text" id="stockInfo">
                            <!-- Aquí se mostrará la información del stock -->
                        </div>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
           
                
                <!-- CAMPO RECEPTOR - SOLO PARA SALIDAS -->
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
                <input type="text" class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" value="{{ old('notes') }}">
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('movements.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                <button type="submit" class="btn btn-success">Registrar Movimiento</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const receptorField = document.getElementById('receptorField');
    const receptorSelect = document.getElementById('receptor');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const form = document.getElementById('movementForm');

    // Función para mostrar/ocultar campo receptor
    function toggleReceptorField() {
        if (typeSelect.value === 'salida') {
            receptorField.style.display = 'block';
            receptorSelect.setAttribute('required', 'required');
            updateMaxQuantity(); // Actualizar max cuando cambia a salida
        } else {
            receptorField.style.display = 'none';
            receptorSelect.removeAttribute('required');
            receptorSelect.value = '';
            quantityInput.removeAttribute('max'); // Quitar límite para entradas
        }
    }

    // Función para actualizar el máximo permitido según el stock
    function updateMaxQuantity() {
        if (typeSelect.value === 'salida' && productSelect.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const stock = parseInt(selectedOption.getAttribute('data-stock'));
            
            // Establecer el máximo como el stock disponible
            quantityInput.setAttribute('max', stock);
            quantityInput.setAttribute('title', `Máximo permitido: ${stock} unidades`);
            
            // Si la cantidad actual es mayor al stock, ajustarla
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = stock;
            }
        } else {
            quantityInput.removeAttribute('max');
            quantityInput.removeAttribute('title');
        }
    }

    // Función para validar stock antes de enviar
    function validateStock() {
        if (typeSelect.value === 'salida' && productSelect.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const stock = parseInt(selectedOption.getAttribute('data-stock'));
            const quantity = parseInt(quantityInput.value);
            
            if (quantity > stock) {
                alert(`❌ Stock insuficiente\n\nSolo hay ${stock} unidades disponibles de "${selectedOption.text}"\nCantidad solicitada: ${quantity}`);
                quantityInput.focus();
                return false;
            }
        }
        return true;
    }

    // Event listeners
    typeSelect.addEventListener('change', toggleReceptorField);
    productSelect.addEventListener('change', updateMaxQuantity);
    quantityInput.addEventListener('input', function() {
        if (typeSelect.value === 'salida') {
            updateMaxQuantity();
        }
    });

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        // Validar receptor
        if (typeSelect.value === 'salida' && !receptorSelect.value) {
            e.preventDefault();
            alert('Por favor seleccione un receptor para la salida');
            receptorSelect.focus();
            return;
        }
        
        // Validar stock
        if (!validateStock()) {
            e.preventDefault();
            return;
        }
    });

    // Inicializar
    toggleReceptorField();
    updateMaxQuantity();
});

function updateStockInfo() {
    const stockInfo = document.getElementById('stockInfo');
    if (typeSelect.value === 'salida' && productSelect.value) {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        const quantity = parseInt(quantityInput.value) || 0;
        
        if (quantity > stock) {
            stockInfo.innerHTML = `<span class="text-danger">
                <i class="bi bi-exclamation-triangle"></i> 
                Stock insuficiente. Máximo permitido: ${stock} unidades
            </span>`;
        } else {
            stockInfo.innerHTML = `<span class="text-success">
                <i class="bi bi-check-circle"></i> 
                Stock disponible: ${stock} unidades
            </span>`;
        }
    } else {
        stockInfo.innerHTML = '';
    }
}

// Agregar este event listener
quantityInput.addEventListener('input', updateStockInfo);
productSelect.addEventListener('change', updateStockInfo);
typeSelect.addEventListener('change', updateStockInfo);
</script>
@endsection