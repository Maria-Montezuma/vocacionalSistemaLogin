<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas de Seguridad</title>
    <link rel="stylesheet" href="{{ asset('css/cambio-contrasena.css') }}">
</head>
<body class="bg-light">
    <div class="container">
        <div class="form-container">
            <h2 class="titulo">Preguntas de Seguridad</h2>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('validar.respuestas') }}">
                @csrf
                <input type="hidden" name="userId" value="{{ $userId }}">

                @foreach($preguntas as $pregunta)
                    <div class="pregunta-container">
                        <div class="form-group">
                            <label class="form-label" for="respuesta_{{ $pregunta->idRespuestasSeguridad }}">
                                {{ $pregunta->PreguntasSeguridad }}
                            </label>
                            <input 
                                type="text" 
                                class="form-control"
                                id="respuesta_{{ $pregunta->idRespuestasSeguridad }}"
                                name="respuestas[{{ $pregunta->idRespuestasSeguridad }}]"
                                required
                                autocomplete="off"
                            >
                        </div>
                    </div>
                @endforeach

                <div class="d-grid">
                    <button type="submit" class=" btn-primary btn-validar">
                        Validar Respuestas
                    </button>
                </div>
            </form>

            <div class="otro-metodo">
                <a href="{{ route('recuperar-contraseña') }}">Probar otro método de recuperación</a>
            </div>
        </div>
    </div>
</body>
</html>