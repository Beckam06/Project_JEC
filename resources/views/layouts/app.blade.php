<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Inventario')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .main-content {
            transition: all 0.3s ease;
            padding: 20px;
        }
        
        .navbar-toggler {
            border: none;
            font-size: 1.5rem;
        }
        
        /* Sidebar para móviles */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                width: 250px;
                z-index: 1000;
                height: 100vh;
                overflow-y: auto;
                top: 0;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
            }
            
            .overlay.show {
                display: block;
            }
            
            .main-content {
                width: 100%;
                padding-top: 70px !important;
            }
            
            /* Navbar mobile - MEJORADO */
            .navbar-mobile {
                height: 56px;
                display: flex;
                align-items: center;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1001;
                background: white;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                padding: 0 15px;
            }
            
            .navbar-mobile-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }
            
            .navbar-mobile-brand {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                margin: 0;
                font-size: 1rem;
                white-space: nowrap;
            }
            
            .btn-home-mobile {
                border: none;
                background: none;
                font-size: 1.2rem;
                color: #6c757d;
                padding: 0.5rem;
                z-index: 1002;
            }
            
            .btn-home-mobile:hover {
                color: #0d6efd;
            }
        }
        
        /* Estilos para desktop */
        @media (min-width: 769px) {
            .main-content {
                padding-top: 20px !important;
                margin-top: 0 !important;
            }
            
            .navbar-mobile {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Mobile MEJORADO con botón de volver -->
    <nav class="navbar navbar-light bg-light d-md-none fixed-top navbar-mobile">
        <div class="navbar-mobile-content">
            <!-- Botón Hamburguesa -->
            <button class="navbar-toggler btn-home-mobile" type="button" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            
            <!-- Título Centrado -->
            <span class="navbar-mobile-brand">
                <i class="bi bi-box-seam"></i> Juventud en Camino
            </span>
            
            <!-- Botón Home/Volver -->
            <a href="{{ route('dashboard') }}" class="btn-home-mobile">
                <i class="bi bi-house"></i>
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Overlay para móviles -->
            <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar py-4" id="sidebar">
                <div class="d-none d-md-block text-center mb-4">
                    <h4>
                        <i class="bi bi-box-seam"></i> Juventud en Camino
                    </h4>
                </div>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link" onclick="closeSidebar()">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link" onclick="closeSidebar()">
                            <i class="bi bi-box me-2"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('movements.index') }}" class="nav-link" onclick="closeSidebar()">
                            <i class="bi bi-arrow-left-right me-2"></i> Movimientos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.requests.index') }}" class="nav-link" onclick="closeSidebar()">
                            <i class="bi bi-clipboard-check me-2"></i> Solicitudes
                            @if($pendingRequestsCount > 0)
                            <span class="badge bg-danger float-end">{{ $pendingRequestsCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 main-content" id="mainContent">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
        
        function closeSidebar() {
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        }
        
        document.getElementById('overlay').addEventListener('click', closeSidebar);
        
        function adjustContent() {
            const mainContent = document.getElementById('mainContent');
            if (window.innerWidth < 768) {
                mainContent.style.paddingTop = '70px';
            } else {
                mainContent.style.paddingTop = '20px';
                mainContent.style.marginTop = '0';
            }
        }
        
        window.addEventListener('load', adjustContent);
        window.addEventListener('resize', adjustContent);
        adjustContent();
    </script>
    @stack('scripts')
</body>
</html>