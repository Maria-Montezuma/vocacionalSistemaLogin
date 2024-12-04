
    // Inicialización de TinyMCE simplificada
    tinymce.init({
        selector: '#descripcion',
        height: 200,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'charmap',
            'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif; font-size: 14px }'
    });

    function toggleForm() {
        document.querySelector('.container').classList.toggle('active');
    }

    // / Función para capturar la pantalla usando Microlink
async function captureWebsite(url) {
    const loadingSpinner = document.getElementById('loading-spinner');
    const previewContainer = document.getElementById('preview-container');
    const websitePreview = document.getElementById('website-preview');
    const errorElement = document.getElementById('url-error');
    const retryButton = document.getElementById('retry-button');

    try {
        loadingSpinner.style.display = 'block';
        previewContainer.style.display = 'none';
        retryButton.style.display = 'none';
        errorElement.style.display = 'none';

        // Usar Microlink API
        const response = await fetch(`https://api.microlink.io?url=${encodeURIComponent(url)}&screenshot=true&meta=false`);
        const data = await response.json();

        if (data.status === 'success' && data.data.screenshot) {
            const img = new Image();
            img.onload = function() {
                websitePreview.innerHTML = '';
                websitePreview.appendChild(img);
                websitePreview.style.display = 'block';
                previewContainer.style.display = 'block';
                loadingSpinner.style.display = 'none';
                retryButton.style.display = 'none';
            };
            img.onerror = function() {
                throw new Error('Error al cargar la imagen');
            };
            img.src = data.data.screenshot.url;
        } else {
            throw new Error('No se pudo obtener la captura');
        }
    } catch (error) {
        loadingSpinner.style.display = 'none';
        errorElement.textContent = 'Error al capturar la vista previa del sitio.';
        errorElement.style.display = 'block';
        retryButton.style.display = 'block';
        console.error('Error:', error);
    }
}

// Eventos para el campo de URL
let timeoutId;
document.getElementById('sitio_web').addEventListener('input', function(e) {
    const url = e.target.value.trim();
    const errorElement = document.getElementById('url-error');

    // Limpiar timeout anterior
    if (timeoutId) clearTimeout(timeoutId);

    // Ocultar mensajes anteriores
    errorElement.style.display = 'none';

    if (url) {
        // Validar formato HTTPS
        if (!url.startsWith('https://')) {
            errorElement.style.display = 'block';
            return;
        }

        // Esperar a que el usuario termine de escribir
        timeoutId = setTimeout(() => {
            captureWebsite(url);
        }, 1000);
    }
});

// Evento para el botón de reintentar
document.getElementById('retry-button').addEventListener('click', function() {
    const url = document.getElementById('sitio_web').value.trim();
    if (url && url.startsWith('https://')) {
        captureWebsite(url);
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const socialInputs = {
        facebook: document.getElementById('facebook'),
        instagram: document.getElementById('instagram'),
        twitter: document.getElementById('twitter'),
        tiktok: document.getElementById('tiktok')
    };

    // Función para validar las URLs de redes sociales
    function validateSocialMedia(input, type) {
        let value = input.value.trim();
        let isValid = true;

        switch(type) {
            case 'facebook':
                if (value && !value.match(/^(?:https?:\/\/)?(?:www\.)?facebook\.com\/[a-zA-Z0-9\.]+$/)) {
                    isValid = false;
                }
                break;
            case 'instagram':
            case 'twitter':
            case 'tiktok':
                if (value && !value.match(/^@?[a-zA-Z0-9._]+$/)) {
                    isValid = false;
                }
                break;
        }

        if (!isValid) {
            input.setCustomValidity('Por favor, introduce un nombre de usuario válido');
            input.classList.add('invalid');
        } else {
            input.setCustomValidity('');
            input.classList.remove('invalid');
        }
    }

    // Agregar eventos de validación
    Object.entries(socialInputs).forEach(([type, input]) => {
        input.addEventListener('input', () => validateSocialMedia(input, type));
        input.addEventListener('blur', () => validateSocialMedia(input, type));
    });
});

