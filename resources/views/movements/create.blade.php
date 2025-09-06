@extends('layouts.app')

@section('title', 'Registrar Movimiento')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Registrar Movimiento de Inventario</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('movements.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Producto *</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                            <option value="">Seleccionar producto</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ (old('product_id', request('product_id')) == $product->id) ? 'selected' : '' }}>
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
                            <option value="entrada" {{ old('type', request('type')) == 'entrada' ? 'selected' : '' }}>Entrada</option>
                            <option value="salida" {{ old('type', request('type')) == 'salida' ? 'selected' : '' }}>Salida</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad *</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas</label>
                        <input type="text" class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" value="{{ old('notes') }}">
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('movements.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                <button type="submit" class="btn btn-success">Registrar Movimiento</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const typeSelect = document.getElementById('type');
        const quantityInput = document.getElementById('quantity');
        
        // Validar que no se retire más stock del disponible
        function validateQuantity() {
            if (productSelect.value && typeSelect.value === 'salida') {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const stockText = selectedOption.text.match(/Stock: (\d+)/);
                if (stockText) {
                    const availableStock = parseInt(stockText[1]);
                    const quantity = parseInt(quantityInput.value);
                    
                    if (quantity > availableStock) {
                        alert('No hay suficiente stock disponible. Stock actual: ' + availableStock);
                        quantityInput.value = availableStock;
                    }
                }
            }
        }
        
        quantityInput.addEventListener('change', validateQuantity);
        typeSelect.addEventListener('change', validateQuantity);
        productSelect.addEventListener('change', validateQuantity);
    });
</script>
@endsection
@endsection