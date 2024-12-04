<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/cambio-contrasena.css') }}">
</head>
<body>
<div class="container mt-5">
    <div class="recovery-container form-container">
                <h2 class="titulo">Recuperar Contraseña</h2>
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
                confirmButtonColor: '#FF6B00'
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
                    confirmButtonColor: 'e2740e'
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
                confirmButtonColor: '#e2740e'
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