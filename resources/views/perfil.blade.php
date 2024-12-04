<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/perfil.js') }}"></script>
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
                    <div class="profile-actions">
                        <button id="toggle-questions" class="btn btn-toggle">
                            @if(isset($respuestasSeguridad) && count($respuestasSeguridad) > 0)
                                Actualizar Preguntas de Seguridad
                            @else
                                Configurar Preguntas de Seguridad
                            @endif
                        </button>
                        <form action="{{ route('logout') }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" class="btn logout-btn">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="profile-content">
                <div class="card">
                    <div class="card-title">
                        <span>Información de Contacto</span>
                    </div>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            {{ $usuario->CorreoUsuario }}
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $usuario->DireccionUsuario }}
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-venus-mars"></i>
                            {{ $genero }}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">
                        <span>Sobre mí</span>
                    </div>
                    <p>{{ $usuario->DescripcionUsuario }}</p>
                </div>
            </div>

            <div id="questions-section" class="security-questions" style="display: none;">
                <form action="{{ route('respuesta-seguridad.store') }}" method="POST" id="security-questions-form">
                    @csrf
                    <!-- Primera pregunta -->
                    <div class="form-group">
                        <label for="pregunta1">Pregunta 1:</label>
                        <select id="pregunta1" name="preguntas[0][PreguntasSeguridad_idPreguntasSeguridad]" class="form-control question-select" required>
                            <option disabled selected>Selecciona una pregunta</option>
                            @foreach($preguntas as $pregunta)
                                <option value="{{ $pregunta->idPreguntasSeguridad }}">
                                    {{ $pregunta->PreguntasSeguridad }}
                                </option>
                            @endforeach
                        </select>

                        <label class="respuesta" for="respuesta1">Respuesta 1:</label>
                        <input type="text" id="respuesta1" name="preguntas[0][RespuestaSeguridad_hash]" class="form-control" required>
                    </div>

                    <!-- Segunda pregunta -->
                    <div class="form-group">
                        <label for="pregunta2">Pregunta 2:</label>
                        <select id="pregunta2" name="preguntas[1][PreguntasSeguridad_idPreguntasSeguridad]" class="form-control question-select" required>
                        <option disabled selected>Selecciona una pregunta</option>
                            @foreach($preguntas as $pregunta)
                                <option value="{{ $pregunta->idPreguntasSeguridad }}">
                                    {{ $pregunta->PreguntasSeguridad }}
                                </option>
                            @endforeach
                        </select>

                        <label class="respuesta" for="respuesta2">Respuesta 2:</label>
                        <input type="text" id="respuesta2" name="preguntas[1][RespuestaSeguridad_hash]" class="form-control" required>
                    </div>

                    <!-- Tercera pregunta -->
                    <div class="form-group">
                        <label for="pregunta3">Pregunta 3:</label>
                        <select id="pregunta3" name="preguntas[2][PreguntasSeguridad_idPreguntasSeguridad]" class="form-control question-select" required>
                        <option disabled selected>Selecciona una pregunta</option>
                            @foreach($preguntas as $pregunta)
                                <option value="{{ $pregunta->idPreguntasSeguridad }}">
                                    {{ $pregunta->PreguntasSeguridad }}
                                </option>
                            @endforeach
                        </select> 

                        <label class="respuesta" for="respuesta3">Respuesta 3:</label>
                        <input type="text" id="respuesta3" name="preguntas[2][RespuestaSeguridad_hash]" class="form-control" required>
                    </div>

                    <input type="hidden" name="Usuarios_idUsuario" value="{{ $usuario->idUsuario }}">
                    <button type="submit" class="btn submit-btn">Guardar Respuestas</button>
                    <!-- Botón de Cancelar -->
                    <button type="button" id="cancelBtn" class="btn cancel-btn">Cancelar preguntas</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>