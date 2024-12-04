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
<script src="{{ asset('js/recuperar-contrasena.js') }}"></script>
</body>
</html>