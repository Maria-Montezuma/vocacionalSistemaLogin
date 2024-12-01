<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
    <title>Mi Perfil</title>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #e9ecef;
            margin-right: 20px;
        }

        .profile-info h1 {
            margin: 0;
            color: #333;
        }

        .profile-info p {
            margin: 5px 0;
            color: #666;
        }

        .profile-details {
            margin-bottom: 30px;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .description {
            margin-top: 20px;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .btn-toggle {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-toggle:hover {
            background-color: #0056b3;
        }

        .security-questions {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .submit-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        .current-questions {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .current-questions h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .question-item {
            margin-bottom: 10px;
            padding: 8px;
            background-color: white;
            border-radius: 4px;
            border-left: 3px solid #007bff;
        }

        .no-questions {
            color: #6c757d;
            font-style: italic;
        }
    </style>
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

            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>

            <button id="toggle-questions" class="btn-toggle">
                @if(isset($respuestasSeguridad) && count($respuestasSeguridad) > 0)
                    Actualizar Preguntas de Seguridad
                @else
                    Configurar Preguntas de Seguridad
                @endif
            </button>
            
            <div id="questions-section" class="security-questions" style="display: none;">
                <form action="{{ route('respuesta-seguridad.store') }}" method="POST" id="security-questions-form">
                    @csrf
                    <!-- Primera pregunta -->
                    <div class="form-group">
                        <label for="pregunta1">Pregunta 1:</label>
                        <select id="pregunta1" name="preguntas[0][PreguntasSeguridad_idPreguntasSeguridad]" class="question-select" required>
                            <option value="">Selecciona una pregunta</option>
                            @foreach($preguntas as $pregunta)
                                <option value="{{ $pregunta->idPreguntasSeguridad }}">
                                    {{ $pregunta->PreguntasSeguridad }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error-message" id="error-pregunta1"></div>
                    </div>
                    <div class="form-group">
                        <label for="respuesta1">Respuesta 1:</label>
                        <input type="text" id="respuesta1" name="preguntas[0][RespuestaSeguridad_hash]" required>
                    </div>

                    <!-- Segunda pregunta -->
                    <div class="form-group">
                        <label for="pregunta2">Pregunta 2:</label>
                        <select id="pregunta2" name="preguntas[1][PreguntasSeguridad_idPreguntasSeguridad]" class="question-select" required>
                            <option value="">Selecciona una pregunta</option>
                            @foreach($preguntas as $pregunta)
                                <option value="{{ $pregunta->idPreguntasSeguridad }}">
                                    {{ $pregunta->PreguntasSeguridad }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error-message" id="error-pregunta2"></div>
                    </div>
                    <div class="form-group">
                        <label for="respuesta2">Respuesta 2:</label>
                        <input type="text" id="respuesta2" name="preguntas[1][RespuestaSeguridad_hash]" required>
                    </div>

                    <!-- Tercera pregunta -->
                    <div class="form-group">
                        <label for="pregunta3">Pregunta 3:</label>
                        <select id="pregunta3" name="preguntas[2][PreguntasSeguridad_idPreguntasSeguridad]" class="question-select" required>
                            <option value="">Selecciona una pregunta</option>
                            @foreach($preguntas as $pregunta)
                                <option value="{{ $pregunta->idPreguntasSeguridad }}">
                                    {{ $pregunta->PreguntasSeguridad }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error-message" id="error-pregunta3"></div>
                    </div>
                    <div class="form-group">
                        <label for="respuesta3">Respuesta 3:</label>
                        <input type="text" id="respuesta3" name="preguntas[2][RespuestaSeguridad_hash]" required>
                    </div>

                    <input type="hidden" name="Usuarios_idUsuario" value="{{ $usuario->idUsuario }}">
                    <button type="submit" class="submit-btn">Guardar Respuestas</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggle-questions').addEventListener('click', function() {
            const questionsSection = document.getElementById('questions-section');
            questionsSection.style.display = questionsSection.style.display === 'none' ? 'block' : 'none';
        });

        const selects = document.querySelectorAll('.question-select');
        
        function validateQuestions() {
            const selectedValues = new Set();
            let isValid = true;
            
            document.querySelectorAll('.error-message').forEach(error => {
                error.style.display = 'none';
                error.textContent = '';
            });

            selects.forEach((select, index) => {
                if (select.value) {
                    if (selectedValues.has(select.value)) {
                        document.getElementById(`error-pregunta${index + 1}`).textContent = 'Por favor seleccione preguntas diferentes';
                        document.getElementById(`error-pregunta${index + 1}`).style.display = 'block';
                        isValid = false;
                    }
                    selectedValues.add(select.value);
                }
            });

            return isValid;
        }

        document.getElementById('security-questions-form').addEventListener('submit', function(e) {
            if (!validateQuestions()) {
                e.preventDefault();
            }
        });

        selects.forEach(select => {
            select.addEventListener('change', validateQuestions);
        });
    </script>
</body>
</html>