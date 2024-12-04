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
    document.getElementById('cambioContrasenaForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("{{ route('actualizar.contrasena') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Contraseña actualizada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Cambia esta ruta por la ruta a la que quieres redirigir
                    window.location.href = "{{ route('registro') }}";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Hubo un error al actualizar la contraseña'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al procesar tu solicitud'
            });
            console.error('Error:', error);
        });
    });
    </script>
</body>
</html>