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
        
        .house-badge {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .blur-background {
            filter: blur(5px);
            pointer-events: none;
            user-select: none;
        }
        
        .disabled-content {
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
    <div class="modal fade" id="pinModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-shield-lock"></i> Verificación de Acceso
                    </h5>
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
            <div class="col-lg-10">
                <div class="card glass-card">
                    <div class="card-header text-center bg-primary text-white position-relative">
                        <h2 class="mb-1">
                            <i class="bi bi-list-check"></i> 
                            Historial de Solicitudes
                        </h2>
                        <div class="d-flex justify-content-center align-items-center mt-2">
                            <small class="me-2 text-white-50">Vista para:</small>
                            <span class="house-badge me-2" id="history-house-name">
                                <i class="bi bi-house"></i> 
                                <span id="current-house-text">Casa no seleccionada</span>
                            </span>
                            <button onclick="changeHouse()" class="btn btn-sm btn-light">
                                <i class="bi bi-arrow-repeat"></i> Cambiar
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
    <!-- ✅ AGREGAR ESTO AL INICIO para mostrar mensaje de éxito -->
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <!-- Alert para cuando no hay casa seleccionada -->
                        <div class="alert alert-info" id="no-house-alert">
                            <i class="bi bi-info-circle"></i> 
                            Selecciona una casa para ver las solicitudes correspondientes.
                        </div>

                        @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Solicitante</th>
                                        <th>Propósito</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                    <tr>
                                        <td>
                                            @if($request->product)
                                                {{ $request->product->name }}
                                            @else
                                                <strong>{{ $request->new_product_name ?? 'Producto solicitado' }}</strong>
                                                <br>
                                                <small class="text-warning">
                                                    <i class="bi bi-clock"></i> Nuevo producto - En evaluación
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $request->quantity_requested }}</td>
                                        <td>{{ $request->requester_name }}</td>
                                        <td>{{ $request->purpose }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status == 'aprobada' ? 'success' : ($request->status == 'pendiente' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 4rem; color: #6c757d;"></i>
                            <h4 class="mt-3" id="no-requests-title">No hay solicitudes</h4>
                            <p class="text-muted" id="no-requests-message">Aún no se han realizado solicitudes</p>
                            <a href="{{ route('client.requests.create') }}" class="btn btn-primary" id="create-request-btn">
                                <i class="bi bi-plus-circle"></i> Crear primera solicitud
                            </a>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('client.requests.create') }}" class="btn btn-outline-primary" id="back-to-create-btn">
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
    <script>
        // =============================================
        // SISTEMA DE PIN - MISMOS CÓDIGOS QUE CREATE
        // =============================================
        const HOUSE_PINS = {
            'Casa Amarilla': '2648',
            'Casa Naranja': '1205', 
            'Casa Verde': '1698',
            'Estimulacion': '2018',
            'Clinica': '9867',
            'Mantenimiento': '4578',
            'Cocina': '1256',
            'Carpinteria': '3890',
            'Administracion': '7902'
        };

      

        let currentHouse = null;
        let pinModal = null;

        function initializePinSystem() {
            pinModal = new bootstrap.Modal(document.getElementById('pinModal'), {
                backdrop: 'static',
                keyboard: false
            });
            
            const urlParams = new URLSearchParams(window.location.search);
            const urlHouse = urlParams.get('house');
            const savedHouse = localStorage.getItem('user_house');
            
         if (urlHouse) {
    setCurrentHouse(urlHouse);
    enableContent();
} else if (savedHouse) {
    setCurrentHouse(savedHouse);
    enableContent();
} else if ("{{ session('house') }}") {
    // ✅ NUEVO: Si viene de un redirect con casa en sesión
    setCurrentHouse("{{ session('house') }}");
    enableContent();
} else {
    disableContent();
    setTimeout(() => {
        pinModal.show();
        document.getElementById('house-select').focus();
    }, 100);
}
        }

        function setCurrentHouse(house) {
            currentHouse = house;
            localStorage.setItem('user_house', house);
            updateHouseDisplay(house);
            updateLinks(house);
        }

        function updateHouseDisplay(house) {
            const historyDisplay = document.getElementById('current-house-text');
            const noHouseAlert = document.getElementById('no-house-alert');
            const noRequestsTitle = document.getElementById('no-requests-title');
            const noRequestsMessage = document.getElementById('no-requests-message');
            
            if (historyDisplay) historyDisplay.textContent = house;
            if (noHouseAlert) noHouseAlert.style.display = 'none';
            
            if (noRequestsTitle && noRequestsMessage) {
                noRequestsTitle.textContent = 'No hay solicitudes para ' + house;
                noRequestsMessage.textContent = 'No se encontraron solicitudes para esta casa.';
            }
        }

        function updateLinks(house) {
            const createRequestBtn = document.getElementById('create-request-btn');
            const backToCreateBtn = document.getElementById('back-to-create-btn');
            
            if (createRequestBtn) {
                createRequestBtn.href = "{{ route('client.requests.create') }}";
            }
            if (backToCreateBtn) {
                backToCreateBtn.href = "{{ route('client.requests.create') }}";
            }
        }

        function disableContent() {
            document.getElementById('main-content').classList.add('blur-background', 'disabled-content');
            document.getElementById('no-house-alert').style.display = 'block';
        }

        function enableContent() {
            document.getElementById('main-content').classList.remove('blur-background', 'disabled-content');
            document.getElementById('no-house-alert').style.display = 'none';
        }

        function changeHouse() {
            localStorage.removeItem('user_house');
            currentHouse = null;
            disableContent();
            pinModal.show();
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
                enableContent();
                
                // Recargar para mostrar solo las solicitudes de esa casa
                window.location.href = '{{ route("client.requests.index") }}?house=' + encodeURIComponent(selectedHouse);
                return true;
            } else {
                alert('❌ PIN incorrecto para ' + selectedHouse);
                document.getElementById('pin-input').value = '';
                document.getElementById('pin-input').focus();
                return false;
            }
        }

        // Inicializar sistema de PIN
        document.addEventListener('DOMContentLoaded', function() {
            initializePinSystem();
            
            document.getElementById('verify-pin-btn').addEventListener('click', verifyPin);
            
            document.getElementById('house-select').addEventListener('change', function() {
                const selectedHouse = this.value;
                const pinInfo = document.getElementById('pin-info');
                const pinHintText = document.getElementById('pin-hint-text');
                
                if (selectedHouse && PIN_HINTS[selectedHouse]) {
                    pinHintText.textContent = PIN_HINTS[selectedHouse];
                    pinInfo.style.display = 'block';
                } else {
                    pinInfo.style.display = 'none';
                }
            });
            
            document.getElementById('pin-input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    verifyPin();
                }
            });
        });
    </script>
</body>
</html>