$(document).ready(function() {
    $('#resetForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '/reset-password',
            method: 'POST',
            data: {
                token: $('input[name="token"]').val(),
                ContrasenaUsuario: $('#ContrasenaUsuario').val(),
                ContrasenaUsuario_confirmation: $('#ContrasenaUsuario_confirmation').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Contraseña Actualizada!',
                    text: 'Tu contraseña ha sido restablecida exitosamente.',
                    confirmButtonText: 'Iniciar Sesión',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/registro';
                    }
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar la contraseña. Por favor, intenta nuevamente.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});
