<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas de Seguridad</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pregunta-container {
            margin-bottom: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            background-color: #fff;
        }
        .titulo {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-validar {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #e2740e;
            border-color: #e2740e;
        }
        .btn-primary:hover {
            background-color: #f79840;
            border-color: #f79840;
        }
        .btn-primary:focus {
            background-color: #f79840;
            border-color: #f79840;
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 0, 0.25);
        }
        .btn-primary:active {
            background-color: #f79840 !important;
            border-color: #f79840 !important;
        }
        .otro-metodo {
            text-align: center;
            margin-top: 20px;
        }
        .otro-metodo a {
            color: #e2740e;
            text-decoration: none;
        }
        .otro-metodo a:hover {
            text-decoration: underline;
        }
    </style>
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
                    <button type="submit" class="btn btn-primary btn-validar">
                        Validar Respuestas
                    </button>
                </div>
            </form>

            <div class="otro-metodo">
                <a href="{{ route('recuperar-contraseña') }}">Probar otro método de recuperación</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>