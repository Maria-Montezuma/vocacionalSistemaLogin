document.addEventListener('DOMContentLoaded', function() {
    // Función para cambiar método
    window.cambiarMetodo = function(metodo) {
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

    // Función para enviar enlace
    window.enviarEnlaceRecuperacion = async function() {
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
                confirmButtonColor: '#e2740e'
            });
        }
    }

    // Función para verificar preguntas
    window.verificarPreguntas = async function() {
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
});