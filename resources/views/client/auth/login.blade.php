<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4><i class="bi bi-box-seam"></i> Sistema de Solicitudes</h4>
                        <p class="mb-0">Acceso para Clientes</p>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        <form action="{{ route('client.login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </form>

                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="mb-3"><i class="bi bi-info-circle"></i> Credenciales de prueba:</h6>
                            <div class="small">
                                <strong><i class="bi bi-house-fill text-warning"></i> Casa Amarilla:</strong><br>
                                Email: cliente@casaamarilla.com<br>
                                Contraseña: amarilla123<br><br>

                                <strong><i class="bi bi-house-fill text-orange"></i> Casa Naranja:</strong><br>
                                Email: cliente@casanaranja.com<br>
                                Contraseña: naranja123<br><br>

                                <strong><i class="bi bi-house-fill text-success"></i> Casa Verde:</strong><br>
                                Email: cliente@casaverde.com<br>
                                Contraseña: verde123
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>