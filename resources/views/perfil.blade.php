<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> -->
    <title>Mi Perfil Profesional</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --light-color: #ecf0f1;
            --danger-color: #e74c3c;
            --success-color: #27ae60;
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --border-color: #bdc3c7;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f6fa;
            color: var(--text-primary);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .profile-card {
            display: grid;
            grid-template-columns: 300px 1fr;
            min-height: 100vh;
        }

        .profile-sidebar {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem;
        }

        .profile-main {
            padding: 2rem;
        }

        .profile-pic {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid var(--accent-color);
            margin: 0 auto 2rem;
            background-color: var(--light-color);
            display: block;
        }

        .profile-info {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-info h1 {
            font-size: 1.8rem;
            margin: 0;
            color: white;
        }

        .profile-info p {
            color: var(--light-color);
            margin: 0.5rem 0;
            font-size: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--accent-color);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.5rem;
            margin: 2rem 0 1rem;
        }

        .detail-item {
            margin-bottom: 1.5rem;
        }

        .detail-item strong {
            display: block;
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.3rem;
        }

        .detail-item p {
            margin: 0;
            color: var(--text-primary);
        }

        .btn {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            text-align: center;
            margin: 0.5rem 0;
        }

        .logout-btn {
            background-color: var(--danger-color);
            color: white;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .btn-toggle {
            background-color: var(--accent-color);
            color: white;
        }

        .btn-toggle:hover {
            background-color: #2980b9;
        }

        .security-questions {
            background-color: var(--light-color);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .submit-btn {
            background-color: var(--success-color);
            color: white;
        }

        .submit-btn:hover {
            background-color: #219a52;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: none;
        }

        .contact-info {
            margin-top: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--light-color);
        }

        .contact-item i {
            margin-right: 0.5rem;
            color: var(--accent-color);
        }

        @media (max-width: 768px) {
            .profile-card {
                grid-template-columns: 1fr;
            }

            .profile-pic {
                width: 150px;
                height: 150px;
            }

            .container {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-sidebar">
                <div class="profile-pic"></div>
                <div class="profile-info">
                    <h1>{{ session('NombreUsuario') }} {{ session('ApellidoUsuario') }}</h1>
                    <p>{{ session('CorreoUsuario') }}</p>
                </div>

                <div class="contact-info">
                    <div class="section-title">Información de Contacto</div>
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

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn logout-btn">Cerrar Sesión</button>
                </form>
            </div>

            <div class="profile-main">
                <div class="section-title">Perfil Profesional</div>
                <div class="description">
                    <p>{{ $usuario->DescripcionUsuario }}</p>
                </div>

                <button id="toggle-questions" class="btn btn-toggle">
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
                            <select id="pregunta1" name="preguntas[0][PreguntasSeguridad_idPreguntasSeguridad]" class="form-control question-select" required>
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
                            <input type="text" id="respuesta1" name="preguntas[0][RespuestaSeguridad_hash]" class="form-control" required>
                        </div>

                        <!-- Segunda pregunta -->
                        <div class="form-group">
                            <label for="pregunta2">Pregunta 2:</label>
                            <select id="pregunta2" name="preguntas[1][PreguntasSeguridad_idPreguntasSeguridad]" class="form-control question-select" required>
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
                            <input type="text" id="respuesta2" name="preguntas[1][RespuestaSeguridad_hash]" class="form-control" required>
                        </div>

                        <!-- Tercera pregunta -->
                        <div class="form-group">
                            <label for="pregunta3">Pregunta 3:</label>
                            <select id="pregunta3" name="preguntas[2][PreguntasSeguridad_idPreguntasSeguridad]" class="form-control question-select" required>
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
                            <input type="text" id="respuesta3" name="preguntas[2][RespuestaSeguridad_hash]" class="form-control" required>
                        </div>

                        <input type="hidden" name="Usuarios_idUsuario" value="{{ $usuario->idUsuario }}">
                        <button type="submit" class="btn submit-btn">Guardar Respuestas</button>
                    </form>
                </div>
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