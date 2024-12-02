<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <style>
        :root {
            --primary-color: #e2740e;
            --secondary-color: #f79840; 
            --accent-color: #ffb380;
            --light-color: #fff5eb;
            --danger-color: #ff4d4d;
            --success-color: #40b882;
            --text-primary: #2c1810;
            --text-secondary: #8c6753;
            --border-color: #ffe4cc;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #fffcf8;
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-card {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            background: transparent;
        }

        .profile-header {
            background: white;
            border-radius: 5px;
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            box-shadow: 0 4px 20px #cdbdbd;
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--secondary-color);
            border: 4px solid white;
            box-shadow: 0 0 20px rgba(226,116,14,0.2);
        }

        .profile-info h1 {
            font-size: 1.5rem;
            margin: 0;
            color: var(--primary-color);
        }

        .profile-info p {
            color: var(--text-secondary);
            margin: 0.5rem 0;
        }

        .profile-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        

        .logout-btn:hover {
            background-color: #cbc6c6;
        }

        .btn-toggle {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-toggle:hover {
            background-color: var(--secondary-color);
        }

        .profile-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 20px #cdbdbd;
        }

        .card-title {
            font-size: 1.25rem;
            color: #666;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .contact-info {
            display: grid;
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f3f3f3;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            border-color: #40b882;
        }

        

        .security-questions {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 20px rgba(226,116,14,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: var(--light-color);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
        }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }

            .profile-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .profile-content {
                grid-template-columns: 1fr;
            }
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
                            <option value="">Selecciona una pregunta</option>
                            @foreach($preguntas as $pregunta)
                                <option value="{{ $pregunta->idPreguntasSeguridad }}">
                                    {{ $pregunta->PreguntasSeguridad }}
                                </option>
                            @endforeach
                        </select>
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

    <script>
        // El JavaScript permanece igual
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