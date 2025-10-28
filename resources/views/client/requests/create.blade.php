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
        
        .product-row {
            padding: 10px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }

        .remove-product:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
        
        /* Estilos para pestañas */
        .tab-container {
            background: linear-gradient(45deg, #e8f5e8, #c8e6c9);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px dashed #4caf50;
        }
        
        .nav-tabs {
            border: none;
            margin-bottom: 20px;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-radius: 10px;
            margin: 0 5px;
            padding: 12px 24px;
            font-weight: 500;
            color: #555;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        
        .nav-tabs .nav-link:not(.active):hover {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
        }
        
        .tab-content {
            min-height: 200px;
        }
        
        .tab-pane {
            padding: 10px 0;
        }
        
        /* Modal */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8) !important;
        }
        
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .blur-background {
            filter: blur(5px);
            pointer-events: none;
            user-select: none;
        }
        
        .disabled-form {
            opacity: 0.5;
            pointer-events: none;
        }
        
        .pin-input {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            letter-spacing: 8px;
        }
    </style>
</head>
<body>
    <!-- Modal de Verificación por PIN -->
    <div class="modal" id="pinModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-shield-lock"></i> Verificación de Acceso
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-house-lock" style="font-size: 3rem; color: #3498db;"></i>
                        <h4 class="text-primary mt-2">Acceso por PIN</h4>
                        <p class="text-muted">Selecciona tu casa e ingresa el PIN correspondiente</p>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Selecciona tu casa:</label>
                            <select class="form-select form-select-lg" id="house-select">
                                <option value="">-- Elegir casa --</option>
                                <option value="Casa Amarilla">🏠 Casa Amarilla</option>
                                <option value="Casa Naranja">🏠 Casa Naranja</option>
                                <option value="Casa Verde">🏠 Casa Verde</option>
                                <option value="Estimulacion">🧠 Estimulación</option>
                                <option value="Clinica">🏥 Clínica</option>
                                <option value="Mantenimiento">🔧 Mantenimiento</option>
                                <option value="Cocina">👨‍🍳 Cocina</option>
                                <option value="Carpinteria">🪚 Carpintería</option>
                                <option value="Administracion">💼 Administración</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">PIN de acceso:</label>
                            <input type="password" class="form-control form-control-lg pin-input" 
                                   id="pin-input" placeholder="****" maxlength="4" pattern="[0-9]{4}">
                            <div class="form-text text-center">
                                Ingresa el PIN de 4 dígitos de tu casa
                            </div>
                        </div>
                    </div>

                    <!-- Información de PINs -->
                    <div class="alert alert-info mt-3" id="pin-info" style="display: none;">
                        <small>
                            <i class="bi bi-lightbulb"></i> 
                            <strong id="pin-hint-text"></strong>
                        </small>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary btn-lg" id="verify-pin-btn">
                        <i class="bi bi-unlock"></i> Verificar y Acceder
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="main-content">
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

                        <form action="{{ route('client.requests.store') }}" method="POST" class="needs-validation" novalidate id="request-form">
                            @csrf

                            <!-- Switch para tipo de producto NUEVO -->
                            <div class="switch-producto">
                                <div class="form-check form-switch form-check-reverse">
                                    <input class="form-check-input" type="checkbox" id="is_new_product" name="is_new_product" value="1">
                                    <label class="form-check-label text-white fw-bold" for="is_new_product">
                                        ¿Solicitar producto NUEVO que no está en el inventario?
                                    </label>
                                </div>
                                <small class="text-white opacity-75">Desliza el interruptor para solicitar un producto nuevo</small>
                            </div>

                            <!-- Sistema de Pestañas para Productos Existentes -->
                            <div id="existing-product-section">
                                <div class="tab-container">
                                    <h5 class="card-title text-success mb-4">
                                        <i class="bi bi-box-seam"></i> Selecciona el tipo de pedido
                                    </h5>
                                    
                                    <ul class="nav nav-tabs" id="productTypeTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="single-tab" data-bs-toggle="tab" 
                                                    data-bs-target="#single-product" type="button" role="tab">
                                                <i class="bi bi-1-circle"></i> Producto Individual
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="multiple-tab" data-bs-toggle="tab" 
                                                    data-bs-target="#multiple-products" type="button" role="tab">
                                                <i class="bi bi-list-check"></i> Pedido Múltiple
                                            </button>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content" id="productTypeTabsContent">
                                        <!-- Pestaña Producto Individual -->
                                        <div class="tab-pane fade show active" id="single-product" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Selecciona el producto *</label>
                                                        <select class="form-select form-select-lg product-select-single" 
                                                                id="product_id" name="product_id">
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
                                                        <input type="number" class="form-control form-control-lg quantity-single" 
                                                               id="quantity_requested" name="quantity_requested" 
                                                               min="1" placeholder="¿Cuántas unidades?">
                                                        <div class="stock-warning" id="stock-warning">
                                                            <i class="bi bi-exclamation-triangle"></i> 
                                                            <span id="stock-message"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Pestaña Pedido Múltiple -->
                                        <div class="tab-pane fade" id="multiple-products" role="tabpanel">
                                            <p class="text-muted mb-3">Agrega varios productos a la vez</p>
                                            
                                            <div id="multiple-products-container">
                                                <!-- Fila inicial de producto -->
                                                <div class="product-row row g-2 mb-2 align-items-center">
                                                    <div class="col-md-6">
                                                        <select class="form-select product-select" name="multiple_products[0][product_id]">
                                                            <option value="">Selecciona producto</option>
                                                            @foreach($products as $product)
                                                            <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                                                                {{ $product->name }} (Stock: {{ $product->stock }})
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" class="form-control quantity-input" 
                                                               name="multiple_products[0][quantity]" min="1" placeholder="Cantidad" value="1">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-product" disabled>
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <button type="button" id="add-product-btn" class="btn btn-success btn-sm">
                                                    <i class="bi bi-plus-circle"></i> Agregar otro producto
                                                </button>
                                                <small class="text-muted ms-2">Máximo 10 productos por solicitud</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario para productos NUEVOS -->
                            <div id="new-product-form" style="display: none;">
                                <div class="card" style="background: linear-gradient(45deg, #e3f2fd, #bbdefb); border: 2px dashed #2196f3;">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <i class="bi bi-plus-circle"></i> Solicitar Producto Nuevo
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nombre del nuevo producto *</label>
                                                    <input type="text" class="form-control form-control-lg" 
                                                           name="new_product_name" placeholder="Ej: Monitor LED 24'', Sillas ergonómicas, etc.">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Descripción del producto *</label>
                                            <textarea class="form-control" name="new_product_description" rows="3" 
                                                      placeholder="Describa las características y uso del producto..."></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Cantidad solicitada *</label>
                                            <input type="number" class="form-control form-control-lg" 
                                                   name="new_product_quantity" min="1" placeholder="¿Cuántas unidades necesita?">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información básica CON CASA AUTOMÁTICA -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Casa solicitante</label>
                                        <div class="alert alert-info py-2" id="house-display-card">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-house-check fs-5 me-2"></i>
                                                <div>
                                                    <strong id="current-house-display">Casa no seleccionada</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <a href="javascript:void(0)" onclick="changeHouse()" class="text-decoration-none">
                                                            <i class="bi bi-arrow-repeat"></i> Cambiar casa
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Campo oculto que se enviará con el formulario -->
                                        <input type="hidden" name="receptor" id="hidden-house-input" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">¿Quién solicita? *</label>
                                        <input type="text" class="form-control form-control-lg" 
                                               name="requester_name" placeholder="Tu nombre completo" required>
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
                                <a href="{{ route('client.requests.index') }}" class="btn btn-outline-secondary btn-lg" id="view-requests-btn">
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
        // =============================================
        // SISTEMA DE PIN - CONFIGURACIÓN
        // =============================================
        const HOUSE_PINS = {
            'Casa Amarilla': '1111',
            'Casa Naranja': '2222', 
            'Casa Verde': '3333',
            'Estimulacion': '4444',
            'Clinica': '5555',
            'Mantenimiento': '6666',
            'Cocina': '7777',
            'Carpinteria': '8888',
            'Administracion': '9999'
        };

        let currentHouse = null;
        let pinModal = null;
        let productCounter = 1;
        const MAX_PRODUCTS = 10;

        // =============================================
        // FUNCIONES PRINCIPALES
        // =============================================
        function initializePinSystem() {
            const savedHouse = localStorage.getItem('user_house');
            
            if (savedHouse) {
                setCurrentHouse(savedHouse);
                enableForm();
            } else {
                disableForm();
                // MOSTRAR EL MODAL INMEDIATAMENTE
                showPinModal();
            }
        }

        function showPinModal() {
            const modalElement = document.getElementById('pinModal');
            pinModal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            pinModal.show();
            document.getElementById('house-select').focus();
        }

        function setCurrentHouse(house) {
            currentHouse = house;
            localStorage.setItem('user_house', house);
            updateHouseDisplay(house);
            updateIndexLink(house);
        }

        function updateHouseDisplay(house) {
            const houseDisplay = document.getElementById('current-house-display');
            const hiddenInput = document.getElementById('hidden-house-input');
            const houseCard = document.getElementById('house-display-card');
            
            if (houseDisplay) houseDisplay.textContent = house;
            if (hiddenInput) hiddenInput.value = house;
            
            if (houseCard) {
                houseCard.className = 'alert py-2';
                if (house.includes('Amarilla')) houseCard.classList.add('alert-warning');
                else if (house.includes('Naranja')) houseCard.classList.add('alert-warning');
                else if (house.includes('Verde')) houseCard.classList.add('alert-success');
                else houseCard.classList.add('alert-info');
            }
        }

        function updateIndexLink(house) {
            const viewRequestsBtn = document.getElementById('view-requests-btn');
            if (viewRequestsBtn) {
                viewRequestsBtn.href = "{{ route('client.requests.index') }}?house=" + encodeURIComponent(house);
            }
        }

        function disableForm() {
            document.getElementById('main-content').classList.add('blur-background', 'disabled-form');
            document.getElementById('request-form').classList.add('disabled-form');
        }

        function enableForm() {
            document.getElementById('main-content').classList.remove('blur-background', 'disabled-form');
            document.getElementById('request-form').classList.remove('disabled-form');
        }

        function changeHouse() {
            localStorage.removeItem('user_house');
            currentHouse = null;
            disableForm();
            showPinModal();
            
            const houseDisplay = document.getElementById('current-house-display');
            const hiddenInput = document.getElementById('hidden-house-input');
            
            if (houseDisplay) houseDisplay.textContent = 'Casa no seleccionada';
            if (hiddenInput) hiddenInput.value = '';
        }

        function verifyPin() {
            const selectedHouse = document.getElementById('house-select').value;
            const enteredPin = document.getElementById('pin-input').value;
            
            if (!selectedHouse) {
                alert('❌ Primero selecciona una casa');
                document.getElementById('house-select').focus();
                return false;
            }
            
            if (enteredPin.length !== 4) {
                alert('❌ El PIN debe tener exactamente 4 dígitos');
                document.getElementById('pin-input').focus();
                return false;
            }
            
            if (HOUSE_PINS[selectedHouse] === enteredPin) {
                setCurrentHouse(selectedHouse);
                pinModal.hide();
                enableForm();
                
                showTempAlert('success', `✅ Acceso concedido a ${selectedHouse}`);
                return true;
            } else {
                alert('❌ PIN incorrecto para ' + selectedHouse);
                document.getElementById('pin-input').value = '';
                document.getElementById('pin-input').focus();
                return false;
            }
        }

        function showTempAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const tempAlert = document.createElement('div');
            tempAlert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            tempAlert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            tempAlert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            document.body.appendChild(tempAlert);
            setTimeout(() => tempAlert.remove(), 3000);
        }

        // =============================================
        // SISTEMA DE PRODUCTOS MÚLTIPLES
        // =============================================
        function addProductRow() {
            if (productCounter >= MAX_PRODUCTS) {
                alert(`❌ Máximo ${MAX_PRODUCTS} productos por solicitud`);
                return;
            }

            const container = document.getElementById('multiple-products-container');
            const newRow = document.createElement('div');
            newRow.className = 'product-row row g-2 mb-2 align-items-center';
            newRow.innerHTML = `
                <div class="col-md-6">
                    <select class="form-select product-select" name="multiple_products[${productCounter}][product_id]">
                        <option value="">Selecciona producto</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                            {{ $product->name }} (Stock: {{ $product->stock }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control quantity-input" 
                           name="multiple_products[${productCounter}][quantity]" min="1" 
                           placeholder="Cantidad" value="1">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-product">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(newRow);
            productCounter++;
            
            document.querySelectorAll('.remove-product').forEach(btn => {
                btn.disabled = false;
            });

            newRow.querySelector('.product-select').addEventListener('change', checkMultipleStock);
            newRow.querySelector('.quantity-input').addEventListener('input', checkMultipleStock);
            newRow.querySelector('.remove-product').addEventListener('click', removeProductRow);
        }

        function removeProductRow(e) {
            const row = e.target.closest('.product-row');
            const rows = document.querySelectorAll('.product-row');
            
            if (rows.length > 1) {
                row.remove();
                productCounter--;
                
                if (document.querySelectorAll('.product-row').length === 1) {
                    document.querySelector('.remove-product').disabled = true;
                }
                
                renumberProductRows();
            }
        }

        function checkMultipleStock() {
            const rows = document.querySelectorAll('.product-row');
            rows.forEach(row => {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const stock = select.options[select.selectedIndex]?.getAttribute('data-stock');
                
                if (select.value && quantityInput.value && stock) {
                    const availableStock = parseInt(stock);
                    const quantity = parseInt(quantityInput.value);
                    
                    if (quantity > availableStock) {
                        quantityInput.style.borderColor = '#dc3545';
                        quantityInput.title = `Stock insuficiente. Solo hay ${availableStock} unidades`;
                    } else {
                        quantityInput.style.borderColor = '';
                        quantityInput.title = '';
                    }
                }
            });
        }

        function renumberProductRows() {
            const rows = document.querySelectorAll('.product-row');
            rows.forEach((row, index) => {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                
                select.name = `multiple_products[${index}][product_id]`;
                quantityInput.name = `multiple_products[${index}][quantity]`;
            });
        }

        function getMultipleProductsData() {
            const products = [];
            document.querySelectorAll('.product-row').forEach(row => {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                
                if (select.value && quantityInput.value) {
                    products.push({
                        product_id: select.value,
                        quantity: quantityInput.value
                    });
                }
            });
            return products;
        }

        // =============================================
        // SISTEMA DEL FORMULARIO
        // =============================================
        function checkStock() {
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity_requested');
            const stockWarning = document.getElementById('stock-warning');
            const stockMessage = document.getElementById('stock-message');
            
            if (productSelect.value && quantityInput.value) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const availableStock = parseInt(selectedOption.getAttribute('data-stock'));
                const quantity = parseInt(quantityInput.value);
                
                if (quantity > availableStock) {
                    stockMessage.textContent = `⚠️ Advertencia: Solo hay ${availableStock} unidades disponibles. 
                                            La solicitud será procesada parcialmente.`;
                    stockWarning.style.display = 'block';
                } else {
                    stockWarning.style.display = 'none';
                }
            }
        }

        function updateRequiredFields() {
            const activeTab = document.querySelector('.tab-pane.active');
            
            document.querySelectorAll('#existing-product-section [required]').forEach(el => {
                el.removeAttribute('required');
            });
            
            if (activeTab && activeTab.id === 'single-product') {
                const productSelect = document.querySelector('#single-product .product-select-single');
                const quantityInput = document.querySelector('#single-product .quantity-single');
                if (productSelect) productSelect.setAttribute('required', 'required');
                if (quantityInput) quantityInput.setAttribute('required', 'required');
            }
        }

        // =============================================
        // INICIALIZACIÓN PRINCIPAL
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            // INICIALIZAR SISTEMA DE PIN - ESTO ES LO MÁS IMPORTANTE
            initializePinSystem();
            
            // Configurar botón de verificación de PIN
            document.getElementById('verify-pin-btn').addEventListener('click', verifyPin);
            
            // Permitir enviar con Enter en el PIN
            document.getElementById('pin-input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    verifyPin();
                }
            });

            // Manejar cuando se cierra el modal con la X
            document.getElementById('pinModal').addEventListener('hidden.bs.modal', function() {
                if (!currentHouse) {
                    // Si cierran el modal sin seleccionar casa, mantener deshabilitado
                    disableForm();
                }
            });

            // Validar que tenga casa seleccionada antes de enviar el formulario
            document.getElementById('request-form').addEventListener('submit', function(e) {
                if (!currentHouse) {
                    e.preventDefault();
                    alert('❌ ERROR: No tienes una casa seleccionada');
                    changeHouse();
                    return false;
                }
            });

            // =============================================
            // CONFIGURACIÓN DEL FORMULARIO
            // =============================================
            const toggleSwitch = document.getElementById('is_new_product');
            const existingSection = document.getElementById('existing-product-section');
            const newForm = document.getElementById('new-product-form');

            if (toggleSwitch) {
                toggleSwitch.addEventListener('change', function() {
                    if (this.checked) {
                        existingSection.style.display = 'none';
                        newForm.style.display = 'block';
                        document.querySelectorAll('#existing-product-section [required]').forEach(el => {
                            el.removeAttribute('required');
                        });
                    } else {
                        existingSection.style.display = 'block';
                        newForm.style.display = 'none';
                        updateRequiredFields();
                    }
                });
            }

            const tabTriggers = [document.getElementById('single-tab'), document.getElementById('multiple-tab')];
            tabTriggers.forEach(tab => {
                tab.addEventListener('click', function() {
                    setTimeout(updateRequiredFields, 100);
                });
            });

            document.getElementById('product_id').addEventListener('change', checkStock);
            document.getElementById('quantity_requested').addEventListener('input', checkStock);

            document.getElementById('add-product-btn').addEventListener('click', addProductRow);

            document.querySelectorAll('.remove-product').forEach(btn => {
                btn.addEventListener('click', removeProductRow);
            });
            
            document.querySelectorAll('.product-select').forEach(select => {
                select.addEventListener('change', checkMultipleStock);
            });
            
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', checkMultipleStock);
            });

            const form = document.querySelector('form[action*="store"]');
            form.addEventListener('submit', function(e) {
                const isNewProduct = document.getElementById('is_new_product').checked;
                
                if (isNewProduct) {
                    const newProductName = document.querySelector('input[name="new_product_name"]').value;
                    const newProductQuantity = document.querySelector('input[name="new_product_quantity"]').value;
                    
                    if (!newProductName || !newProductQuantity) {
                        e.preventDefault();
                        alert('❌ Para producto nuevo, debes completar nombre y cantidad');
                        return false;
                    }
                } else {
                    const activeTab = document.querySelector('.tab-pane.active');
                    
                    if (activeTab.id === 'single-product') {
                        const productSelect = document.getElementById('product_id');
                        const quantityInput = document.getElementById('quantity_requested');
                        
                        if (!productSelect.value || !quantityInput.value) {
                            e.preventDefault();
                            alert('❌ Debes seleccionar un producto y cantidad para el pedido individual');
                            return false;
                        }
                    } else if (activeTab.id === 'multiple-products') {
                        const multipleProducts = getMultipleProductsData();
                        if (multipleProducts.length === 0) {
                            e.preventDefault();
                            alert('❌ Debes agregar al menos un producto al pedido múltiple');
                            return false;
                        }
                    }
                }
            });

            updateRequiredFields();
        });
    </script>
</body>
</html>