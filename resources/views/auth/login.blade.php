<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema JEC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: url('/images/fondo-login.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            background: rgba(0, 123, 255, 0.7);
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }
        
        .login-body {
            padding: 2rem;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .brand-logo {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #007bff;
            background: white;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        /* NUEVO BOTÓN DE SOLICITUDES */
        .btn-requests {
            background: linear-gradient(135deg, #ff6b35, #ff8e53);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            color: white;
            margin-top: 15px;
            width: 100%;
        }
        
        .btn-requests:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
            color: white;
            background: linear-gradient(135deg, #e55a2e, #ff7b3a);
        }
        
        .input-group-text {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-right: none;
        }
        
        .text-white-with-shadow {
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .form-label {
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            font-weight: 600;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .divider-text {
            padding: 0 15px;
            font-size: 0.9em;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-logo">
                    <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                </div>
                <h3 class="text-white-with-shadow mb-1">Juventud en Camino</h3>
                <p class="text-white-with-shadow mb-0 opacity-90">Sistema de Gestión</p>
            </div>
            
            <div class="login-body">
                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center mb-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span class="text-white-with-shadow">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="email" class="form-control" name="email" required 
                                   placeholder="usuario@ejemplo.com">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control" name="password" required 
                                   placeholder="Ingresa tu contraseña">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-login w-100 text-white">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Acceder al Sistema
                    </button>
                </form>

                <!-- NUEVO: DIVISOR Y BOTÓN DE SOLICITUDES -->
                <div class="divider">
                    <span class="divider-text">O</span>
                </div>

                <a href="{{ route('client.requests.create') }}" class="btn btn-requests">
                    <i class="bi bi-clipboard-plus me-2"></i>Realizar Solicitud
                </a>

                <div class="text-center mt-3">
                    <small class="text-white-with-shadow opacity-80">
                        
                    </small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>