<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/cambio-contrasena.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="form-container">
            <div class="titulo">
                <h2>Restablecer Contraseña</h2>
            </div>
            <form id="resetForm">
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Nueva Contraseña -->
                <div class="mb-3">
                    <label for="ContrasenaUsuario" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="ContrasenaUsuario" name="ContrasenaUsuario" required>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="mb-3">
                    <label for="ContrasenaUsuario_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="ContrasenaUsuario_confirmation" name="ContrasenaUsuario_confirmation" required>
                </div>

                <!-- Botón Cambiar Contraseña -->
                <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/reset-password.js') }}"></script>
</body>
</html>
