
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Preguntas de Seguridad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .questions-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="questions-container">
        <h2 class="text-center mb-4">Preguntas de Seguridad</h2>
        <form id="preguntasForm" onsubmit="event.preventDefault(); verificarRespuestas();">
            @csrf
            <input type="hidden" id="userId" value="{{ $userId }}">
            
            @foreach($preguntas as $index => $pregunta)
            <div class="form-group">
                <label for="respuesta{{ $index }}">
                    {{ $pregunta->preguntaSeguridad->PreguntasSeguridad }}
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="respuesta{{ $index }}"
                    name="respuestas[{{ $pregunta->id }}]"
                    required>
            </div>
            @endforeach

            <div class="form-group">
                <label for="nuevaContrasena">Nueva Contraseña</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="nuevaContrasena"
                    required>
            </div>

            <div class="form-group">
                <label for="confirmarContrasena">Confirmar Nueva Contraseña</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="confirmarContrasena"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Verificar Respuestas</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Volver al inicio de sesión</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function verificarRespuestas() {
            const form = document.getElementById('preguntasForm');
            const userId = document.getElementById('userId').value;
            const nuevaContrasena = document.getElementById('nuevaContrasena').value;
            const confirmarContrasena = document.getElementById('confirmarContrasena').value;

            if (nuevaContrasena !== confirmarContrasena) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            const respuestas = {};
            const inputs = form.querySelectorAll('input[name^="respuestas"]');
            inputs.forEach(input => {
                const preguntaId = input.name.match(/\[(\d+)\]/)[1];
                respuestas[preguntaId] = input.value;
            });

            try {
                const response = await fetch('/verificar-respuestas-seguridad', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        userId: userId,
                        respuestas: respuestas,
                        nuevaContrasena: nuevaContrasena
                    })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Contraseña actualizada correctamente',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        window.location.href = '/login';
                    });
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
                    text: 'Ocurrió un error al verificar las respuestas.',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    </script>
</body>
</html>