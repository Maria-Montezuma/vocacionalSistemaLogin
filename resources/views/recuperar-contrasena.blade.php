<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .recovery-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="recovery-container">
        <h2 class="text-center mb-4">Recuperar Contraseña</h2>
        
        <form id="recuperarForm" onsubmit="event.preventDefault(); enviarEnlaceRecuperacion();">
            @csrf
            <div class="form-group">
                <label for="CorreoUsuarioRecuperar">Correo electrónico</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="CorreoUsuarioRecuperar" 
                    placeholder="Ingresa tu correo electrónico"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar enlace de recuperación</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('registro') }}">Volver al inicio de sesión</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function enviarEnlaceRecuperacion() {
            const emailInput = document.getElementById('CorreoUsuarioRecuperar');
            const email = emailInput.value.trim();

            try {
                const response = await fetch('/enviar-enlace-recuperacion', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ CorreoUsuario: email })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: result.message,
                        confirmButtonColor: '#3085d6'
                    });
                    emailInput.value = ''; // Limpiar el campo
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message,
                        confirmButtonColor: '#3085d6'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al enviar el enlace de recuperación.',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    </script>
</body>
</html>