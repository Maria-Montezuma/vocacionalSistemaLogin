document.getElementById('cambioContrasenaForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch(actualizarContrasenaUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Contraseña actualizada correctamente',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = registroUrl;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Hubo un error al actualizar la contraseña'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al procesar tu solicitud'
        });
        console.error('Error:', error);
    });
});