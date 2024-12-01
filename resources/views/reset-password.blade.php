<!DOCTYPE html>
<html>
<head>
    <title>Restablecer Contraseña</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Restablecer Contraseña</div>
                    <div class="card-body">
                        <form id="resetForm">
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="mb-3">
                                <label for="ContrasenaUsuario" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="ContrasenaUsuario" name="ContrasenaUsuario" required>
                            </div>

                            <div class="mb-3">
                                <label for="ContrasenaUsuario_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="ContrasenaUsuario_confirmation" name="ContrasenaUsuario_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
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
                        alert('Contraseña actualizada correctamente');
                        window.location.href = '/login';
                    },
                    error: function(xhr) {
                        alert('Error al actualizar la contraseña');
                    }
                });
            });
        });
    </script>
</body>
</html>