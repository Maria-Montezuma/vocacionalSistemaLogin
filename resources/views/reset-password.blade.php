<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/cambio-contrasena.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resetForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '/reset-password',
                    method: 'POST',
                    data: {
                        token: $('input[name="token"]').val(),
                        ContrasenaUsuario: $('#ContrasenaUsuario').val(),
                        ContrasenaUsuario_confirmation: $('#ContrasenaUsuario_confirmation').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Contraseña Actualizada!',
                            text: 'Tu contraseña ha sido restablecida exitosamente.',
                            confirmButtonText: 'Iniciar Sesión',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/registro';
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo actualizar la contraseña. Por favor, intenta nuevamente.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
