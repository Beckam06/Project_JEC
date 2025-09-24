<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Productos - Sistema de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --success: #27ae60;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            padding: 2rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }
        
        .switch-producto {
            background: linear-gradient(45deg, #6c5ce7, #a29bfe);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px dashed #ddd;
            transition: all 0.3s ease;
        }
        
        .switch-producto:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.2);
        }
        
        .form-check-input {
            width: 50px;
            height: 25px;
        }
        
       .stock-warning {
        border-radius: 10px;
        padding: 12px;
        margin-top: 10px;
        display: none;
        border-left: 4px solid #ff9800;
        background: linear-gradient(45deg, #fff3e0, #ffecb3);
        color: #7d6608;
        font-weight: 500;
    }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card glass-card">
                    <div class="card-header text-center text-white">
                        <div class="mb-3">
                            <i class="bi bi-cart-plus" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="mb-1">Solicitud de Productos</h2>
                        <p class="mb-0 opacity-75">Sistema de Gestión de Inventario</p>
                    </div>
                    
                    <div class="card-body p-4">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form action="{{ route('client.requests.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            
                            <!-- Switch para tipo de producto -->
                            <div class="switch-producto">
                                <div class="form-check form-switch form-check-reverse">
                                    <input class="form-check-input" type="checkbox" id="is_new_product" name="is_new_product" value="1">
                                    <label class="form-check-label text-white fw-bold" for="is_new_product">
                                        ¿Solicitar producto NUEVO que no está en el inventario?
                                    </label>
                                </div>
                                <small class="text-white opacity-75">Desliza el interruptor para solicitar un producto nuevo</small>
                            </div>

                            <!-- Formulario para productos EXISTENTES -->
                            <div id="existing-product-form">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Selecciona el producto *</label>
                                            <select class="form-select form-select-lg" id="product_id" name="product_id" required>
                                                <option value="">Elige un producto disponible</option>
                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                                                    {{ $product->name }} (Stock: {{ $product->stock }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Cantidad necesaria *</label>
                                            <input type="number" class="form-control form-control-lg" 
                                                   id="quantity_requested" name="quantity_requested" 
                                                   min="1" placeholder="¿Cuántas unidades?" required>
                                            <div class="stock-warning" id="stock-warning">
                                                <i class="bi bi-exclamation-triangle"></i> 
                                                <span id="stock-message"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario para productos NUEVOS -->
                            <div id="new-product-form" style="display: none;">
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nombre del nuevo producto *</label>
                                            <input type="text" class="form-control form-control-lg" 
                                                   name="new_product_name" placeholder="Ej: Monitor LED 24'', Sillas ergonómicas, etc.">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Descripción del producto *</label>
                                    <textarea class="form-control" name="new_product_description" rows="3" 
                                              placeholder="Describa las características y uso del producto..."></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Cantidad solicitada *</label>
                                    <input type="number" class="form-control form-control-lg" 
                                           name="new_product_quantity" min="1" placeholder="¿Cuántas unidades necesita?">
                                </div>
                            </div>

                            <!-- Información básica -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">¿Para qué casa es? *</label>
                                        <select class="form-select form-select-lg" name="receptor" required>
                                            <option value="">Selecciona una casa</option>
                                            <option value="Casa Amarilla">🏠 Casa Amarilla</option>
                                            <option value="Casa Naranja">🏠 Casa Naranja</option>
                                            <option value="Casa Verde">🏠 Casa Verde</option>
                                            <option value="Estimulacion">🏠 Estimulacion</option>
                                            <option value="Clinica">🏠 Clinica</option>
                                            <option value="Mantenimiento">🏠 Mantenimiento</option>
                                            <option value="Cocina">🏠 Cocina</option>
                                            <option value="Carpinteria">🏠 Carpinteria</option>
                                            <option value="Administracion">🏠 Administracion</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">¿Quién solicita? *</label>
                                        <input type="text" class="form-control form-control-lg" 
                                               name="requester_name" placeholder="Tu nombre" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Propósito -->
                          <div class="mb-4">
                                <label class="form-label fw-semibold">¿Para qué lo necesitas? *</label>
                                <select class="form-select form-select-lg" name="purpose" required>
                                    <option value="">Selecciona el propósito</option>
                                    <option value="Uso diario">Uso diario</option>
                                    <option value="Evento especial">Evento especial</option>
                                    <option value="Reemplazo">Reemplazo</option>
                                    <option value="Nuevo proyecto">Nuevo proyecto</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <!-- Botones -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-between align-items-center">
                                <a href="{{ route('client.requests.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-list me-2"></i>Ver solicitudes
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1 ms-md-2" id="submit-btn">
                                    <i class="bi bi-send me-2"></i>Enviar solicitud
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="text-center mt-4 text-white">
                    <p>¿Necesitas ayuda? Contacta al administrador del sistema</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleSwitch = document.getElementById('is_new_product');
    const existingForm = document.getElementById('existing-product-form');
    const newForm = document.getElementById('new-product-form');
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity_requested');
    const stockWarning = document.getElementById('stock-warning');
    const stockMessage = document.getElementById('stock-message');
    const submitBtn = document.getElementById('submit-btn');

    // Toggle entre formularios
    toggleSwitch.addEventListener('change', function() {
        if (this.checked) {
            existingForm.style.display = 'none';
            newForm.style.display = 'block';
            // WHY: Para productos nuevos, no validamos stock
            productSelect.removeAttribute('required');
            quantityInput.removeAttribute('required');
            stockWarning.style.display = 'none';
            submitBtn.disabled = false; // ← Aseguramos que el botón esté habilitado
        } else {
            existingForm.style.display = 'block';
            newForm.style.display = 'none';
            // WHY: Para productos existentes, validamos stock
            productSelect.setAttribute('required', 'required');
            quantityInput.setAttribute('required', 'required');
            validateStock(); // Validar stock al cambiar
        }
    });

    // Validación de stock SOLO para productos existentes
        function checkStock() {
        if (!toggleSwitch.checked && productSelect.value && quantityInput.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const availableStock = parseInt(selectedOption.getAttribute('data-stock'));
            const quantity = parseInt(quantityInput.value);
            
            if (quantity > availableStock) {
                stockMessage.textContent = `⚠️ Advertencia: Solo hay ${availableStock} unidades disponibles. 
                                          La solicitud será procesada parcialmente.`;
                stockWarning.style.display = 'block';
                stockWarning.style.background = 'linear-gradient(45deg, #ff9800, #ff5722)';
            } else {
                stockWarning.style.display = 'none';
            }
        }
    }

    // ✅ SOLO CAMBIAR el nombre de la función en los event listeners
    productSelect.addEventListener('change', function() {
        if (!toggleSwitch.checked) {
            checkStock();  // ← Solo cambió el nombre aquí
        }
    });

    quantityInput.addEventListener('input', function() {
        if (!toggleSwitch.checked) {
            checkStock();  // ← Solo cambió el nombre aquí
        }
    });

    // ✅ ELIMINAR la validación de stock del submit
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
});
    </script>
</body>
</html>