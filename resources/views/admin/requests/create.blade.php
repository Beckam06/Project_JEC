@extends('layouts.app')

@section('title', 'Nueva Solicitud')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Nueva Solicitud de Producto</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('requests.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Producto *</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                            <option value="">Seleccionar producto</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }} data-stock="{{ $product->stock }}">
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
                        <label for="quantity_requested" class="form-label">Cantidad Solicitada *</label>
                        <input type="number" class="form-control @error('quantity_requested') is-invalid @enderror" id="quantity_requested" name="quantity_requested" value="{{ old('quantity_requested') }}" min="1" required>
                        @error('quantity_requested')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="stock-warning" class="text-danger d-none">
                            <i class="bi bi-exclamation-triangle"></i> La cantidad solicitada supera el stock disponible.
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="requester_name" class="form-label">Nombre del Solicitante *</label>
                        <input type="text" class="form-control @error('requester_name') is-invalid @enderror" id="requester_name" name="requester_name" value="{{ old('requester_name') }}" required>
                        @error('requester_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="receptor" class="form-label">Receptor *</label>
                        <select class="form-select @error('receptor') is-invalid @enderror" id="receptor" name="receptor" required>
                            <option value="">Seleccionar receptor</option>
                            <option value="Casa Amarilla" {{ old('receptor') == 'Casa Amarilla' ? 'selected' : '' }}>Casa Amarilla</option>
                            <option value="Casa Naranja" {{ old('receptor') == 'Casa Naranja' ? 'selected' : '' }}>Casa Naranja</option>
                            <option value="Casa Verde" {{ old('receptor') == 'Casa Verde' ? 'selected' : '' }}>Casa Verde</option>
                        </select>
                        @error('receptor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="purpose" class="form-label">Propósito de la Solicitud</label>
                <textarea class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" rows="3">{{ old('purpose') }}</textarea>
                @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notas Adicionales</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('requests.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                <button type="submit" class="btn btn-success" id="submit-btn">Enviar Solicitud</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity_requested');
        const stockWarning = document.getElementById('stock-warning');
        const submitBtn = document.getElementById('submit-btn');

        function validateStock() {
            if (productSelect.value && quantityInput.value) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const availableStock = parseInt(selectedOption.getAttribute('data-stock'));
                const quantity = parseInt(quantityInput.value);
                
                if (quantity > availableStock) {
                    stockWarning.classList.remove('d-none');
                    submitBtn.disabled = true;
                    return false;
                } else {
                    stockWarning.classList.add('d-none');
                    submitBtn.disabled = false;
                    return true;
                }
            }
            return true;
        }

        productSelect.addEventListener('change', validateStock);
        quantityInput.addEventListener('input', validateStock);
    });
</script>
@endsection
@endsection