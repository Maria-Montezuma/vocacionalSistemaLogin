<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
    <title>Mi Perfil</title>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-pic"></div>
                <div class="profile-info">
                    <h1>{{ session('NombreUsuario') }} {{ session('ApellidoUsuario') }}</h1>
                    <p>{{ session('CorreoUsuario') }}</p>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-item">
                    <strong>Nombre Completo:</strong> {{ $usuario->NombreUsuario }} {{ $usuario->ApellidoUsuario }}
                </div>
                <div class="detail-item">
                    <strong>Correo:</strong> {{ $usuario->CorreoUsuario }}
                </div>
                <div class="detail-item">
                    <strong>Dirección:</strong> {{ $usuario->DireccionUsuario }}
                </div>
                <div class="detail-item">
                    <strong>Género:</strong> {{ $genero }}
                </div>
                <div class="description">
                    <strong>Descripción:</strong>
                    <p>{{ $usuario->DescripcionUsuario }}</p>
                </div>
            </div>

              <!-- Botón para desplegar las preguntas de seguridad -->
            <button id="toggle-questions" class="btn-toggle">Configurar Preguntas de Seguridad</button>

<!-- Formulario de preguntas de seguridad -->
<div id="questions-section" class="security-questions" style="display: none;">
    <form action="" method="POST">
        @csrf
        <div class="form-group">
            <label for="pregunta1">Pregunta 1:</label>
            <select id="pregunta1" name="pregunta1" class="question-select" required>
                <option value="">Selecciona una pregunta</option>
                <option value="¿Cuál es tu color favorito?">¿Cuál es tu color favorito?</option>
                <option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
            </select>
        </div>
        <div class="form-group">
            <label for="respuesta1">Respuesta 1:</label>
            <input type="text" id="respuesta1" name="respuesta1" required>
        </div>
        <div class="form-group">
            <label for="pregunta2">Pregunta 2:</label>
            <select id="pregunta2" name="pregunta2" class="question-select" required>
                <option value="">Selecciona una pregunta</option>
                <option value="¿Cuál es tu color favorito?">¿Cuál es tu color favorito?</option>
                <option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
            </select>
        </div>
        <div class="form-group">
            <label for="respuesta2">Respuesta 2:</label>
            <input type="text" id="respuesta2" name="respuesta2" required>
        </div>
        
        <button type="submit" class="submit-btn">Guardar</button>
    </form>
</div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <script>
    // Prevenir la navegación hacia atrás o adelante
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.pushState(null, null, location.href); // Reescribe el historial para bloquear navegación
    };

    // Interceptar eventos de teclado para bloquear teclas de navegación (Alt + Izquierda, Ctrl + Izquierda, etc.)
    document.addEventListener('keydown', function (e) {
        if ((e.altKey && e.key === 'ArrowLeft') || 
            (e.altKey && e.key === 'ArrowRight') || 
            (e.ctrlKey && e.key === 'ArrowLeft') || 
            (e.ctrlKey && e.key === 'ArrowRight') || 
            (e.metaKey && e.key === 'ArrowLeft') || 
            (e.metaKey && e.key === 'ArrowRight')) {
            e.preventDefault(); // Prevenir comportamiento predeterminado
        }
    });

    // Cerrar sesión automáticamente después de un tiempo específico
    function cerrarSesionAutomaticamente() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('logout') }}";

        // Agregar token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = "{{ csrf_token() }}";
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }

    // Configurar temporizador de cierre de sesión (1 minuto = 60000 ms)
    const tiempoCierreSesion = 60000; 
    setTimeout(cerrarSesionAutomaticamente, tiempoCierreSesion);

    // Mostrar alerta antes de cerrar sesión (opcional)
    const tiempoAlerta = 45000; // Mostrar a los 45 segundos
    setTimeout(() => alert('Su sesión se cerrará en 15 segundos.'), tiempoAlerta);
    </script>



    <!-- Script para manejar el despliegue y la desactivación de opciones -->
    <script>
        document.getElementById('toggle-questions').addEventListener('click', function() {
            var questionsSection = document.getElementById('questions-section');
            if (questionsSection.style.display === 'none' || questionsSection.style.display === '') {
                questionsSection.style.display = 'block';
            } else {
                questionsSection.style.display = 'none';
            }
        });

        document.querySelectorAll('.question-select').forEach(function(select) {
            select.addEventListener('change', function() {
                var selectedValue = this.value;
                document.querySelectorAll('.question-select').forEach(function(otherSelect) {
                    if (otherSelect !== select) {
                        Array.from(otherSelect.options).forEach(function(option) {
                            if (option.value === selectedValue) {
                                option.disabled = true;
                            } else {
                                option.disabled = false;
                            }
                        });
                    }
                });
            });
        });
    </script>

</body>
</html>
