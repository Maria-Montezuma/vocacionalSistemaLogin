<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/cambio-contrasena.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <div class="container">
        <div class="form-container">
            <h2 class="titulo">Cambiar Contraseña</h2>

            <form id="cambioContrasenaForm">
                @csrf
                <input type="hidden" name="userId" value="{{ $idUsuario }}">

                <div class="mb-3">
                    <label for="ContrasenaUsuario" class="form-label">Nueva Contraseña</label>
                    <input 
                        type="password" 
                        class="form-control @error('ContrasenaUsuario') is-invalid @enderror" 
                        id="ContrasenaUsuario" 
                        name="ContrasenaUsuario" 
                        required
                        minlength="8"
                    >
                    @error('ContrasenaUsuario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="ContrasenaUsuario_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="ContrasenaUsuario_confirmation" 
                        name="ContrasenaUsuario_confirmation" 
                        required
                        minlength="8"
                    >
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    var actualizarContrasenaUrl = "{{ route('actualizar.contrasena') }}";
    var registroUrl = "{{ route('registro') }}";
</script>
<script src="{{ asset('/js/cambio-contrasena.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>