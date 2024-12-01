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
        .recovery-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .recovery-option {
            flex: 1;
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            cursor: pointer;
        }
        .recovery-option.active {
            background-color: #e9ecef;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="recovery-container">
        <h2 class="text-center mb-4">Recuperar Contraseña</h2>
        
        <div class="recovery-options">
            <div class="recovery-option active" onclick="cambiarMetodo('email')">
                Por correo electrónico
            </div>
            <div class="recovery-option" onclick="cambiarMetodo('preguntas')">
                Por preguntas de seguridad
            </div>
        </div>

        <!-- Formulario de recuperación por correo -->
        <form id="emailForm" onsubmit="event.preventDefault(); enviarEnlaceRecuperacion();">
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

        <!-- Formulario de recuperación por preguntas de seguridad -->
        <form id="preguntasForm" style="display: none;" onsubmit="event.preventDefault(); verificarPreguntas();">
            @csrf
            <div class="form-group">
                <label for="CorreoUsuarioPreguntas">Correo electrónico</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="CorreoUsuarioPreguntas" 
                    placeholder="Ingresa tu correo electrónico"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Verificar correo</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('registro') }}">Volver al inicio de sesión</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function cambiarMetodo(metodo) {
            const emailForm = document.getElementById('emailForm');
            const preguntasForm = document.getElementById('preguntasForm');
            const opciones = document.querySelectorAll('.recovery-option');

            opciones.forEach(opcion => opcion.classList.remove('active'));

            if (metodo === 'email') {
                emailForm.style.display = 'block';
                preguntasForm.style.display = 'none';
                opciones[0].classList.add('active');
            } else {
                emailForm.style.display = 'none';
                preguntasForm.style.display = 'block';
                opciones[1].classList.add('active');
            }
        }

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
                    emailInput.value = '';
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

        async function verificarPreguntas() {
    const emailInput = document.getElementById('CorreoUsuarioPreguntas');
    const email = emailInput.value.trim();

    if (!email) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor ingrese un correo electrónico',
            confirmButtonColor: '#3085d6'
        });
        return;
    }

    try {
        const response = await fetch('/verificar-correo-preguntas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ CorreoUsuario: email })
        });

        const result = await response.json();

        if (result.status === 'success') {
            window.location.href = `/preguntas-seguridad/${result.userId}`;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'Ocurrió un error al verificar el correo.',
                confirmButtonColor: '#3085d6'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al verificar el correo. Por favor intente nuevamente.',
            confirmButtonColor: '#3085d6'
        });
    }
}
    </script>
</body>
</html>