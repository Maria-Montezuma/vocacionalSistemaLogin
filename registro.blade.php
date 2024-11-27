<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
</head>

<body>
<div class="container">
        <div class="form-container">
            <!-- Formulario de Login -->
            <div class="form signin-form">
                <h2>Iniciar Sesión</h2>
                <form action="{{ route('registro.submit') }}" method="POST">
            @csrf
                    
                    <!-- El resto del formulario de registro permanece igual -->
                    <div class="row">
                        <div class="col">
                            <label for="nombres">
                                <i class="fas fa-user"></i> Nombres:
                            </label>
                            <input type="text" id="nombres" name="NombreUsuario" required>
                        </div>
                        <div class="col">
                            <label for="apellidos">
                                <i class="fas fa-user"></i> Apellidos:
                            </label>
                            <input type="text" id="apellidos" name="ApellidoUsuario" required>
                        </div>
                        <div class="col">
                            <label for="genero">
                                <i class="fas fa-venus-mars"></i>Genero:
                            </label>
                            <select id="genero" name="Generos_idGenero" required>
                                <option disabled selected>Seleccionar...</option>
                                @foreach($generos as $genero)
                                    <option value="{{ $genero->idGenero }}">{{ $genero->NombreGenero }}</option>
                                @endforeach
                            </select>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="cedula">
                                <i class="fas fa-id-card"></i> Cédula:
                            </label>
                            <input type="text" id="cedula" name="CedulaUsuario" required>
                        </div>
                        <div class="col">
                            <label for="nacionalidad">
                            <i class="fas fa-globe"></i> Nacionalidad:
                            </label>
                            <select id="nacionalidad" name="Nacionalidades_idNacionalidad" required>
                                <option disabled selected>Seleccionar...</option>
                                @foreach($nacionalidades as $nacionalidad)
                                    <option value="{{ $nacionalidad->idNacionalidad }}">{{ $nacionalidad->NombreNacionalidad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="fecha_nacimiento">
                                <i class="fas fa-calendar-alt"></i> F. de Nacimiento:
                            </label>
                            <input type="date" id="fecha_nacimiento" name="FechaNacimientoUsuario" required>
                        </div>
                    </div>

                    <div class="col">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Correo Electrónico:
                        </label>
                        <input type="email" id="email" name="CorreoUsuario" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="clave">
                                <i class="fas fa-lock"></i> Contraseña:
                            </label>
                            <input type="password" id="clave" name="ContrasenaUsuario" required>
                        </div>
                        <div class="col">
                            <label for="confirm-clave">
                                <i class="fas fa-lock"></i> Confirmar la Contraseña:
                            </label>
                            <input type="password" id="confirm-clave" name="ContrasenaUsuario_confirmation" required>
                        </div>
                    </div>

                    <div class="col">
                        <label for="direccion">
                            <i class="fas fa-home"></i> Dirección Completa:
                        </label>
                        <input type="text" id="direccion" name="DireccionUsuario" required>
                    </div>


                    <!-- Nuevo campo para sitio web personal -->
                    <div class="col">
    <label for="sitio_web">
        <i class="fas fa-globe"></i> Sitio Web Personal:
    </label>
    <input type="url" 
           id="sitio_web" 
           name="sitio_web" 
           placeholder="https://ejemplo.com"
           pattern="https://.*"
           title="La URL debe comenzar con https://">
    <div class="url-error" id="url-error">La URL debe comenzar con https://</div>
    <div class="loading-spinner" id="loading-spinner">
        <i class="fas fa-spinner fa-spin"></i> Capturando vista previa...
    </div>
    <div class="preview-container" id="preview-container">
        <h4>Vista previa del sitio:</h4>
        <div class="website-preview" id="website-preview"></div>
        <button type="button" class="retry-button" id="retry-button">
            <i class="fas fa-redo"></i> Reintentar captura
        </button>
    </div>
</div>
                     <!-- Nuevo bloque para redes sociales -->
                <div class="social-media-section">
                    
                    <div class="row">
                        <div class="col">
                            <label for="facebook">
                            <i class="fab fa-facebook-f"></i>Facebook:
                            </label>
                            <input type="text" 
                                   id="facebook" 
                                   name="facebook" 
                                   placeholder="Usuario o URL de Facebook"
                                   pattern="^(?:https?:\/\/)?(?:www\.)?facebook\.com\/[a-zA-Z0-9\.]+$">
                        </div>
                        <div class="col">
                            <label for="instagram">
                                <i class="fab fa-instagram"></i> Instagram:
                            </label>
                            <input type="text" 
                                   id="instagram" 
                                   name="instagram" 
                                   placeholder="@usuario_instagram"
                                   pattern="^@?[a-zA-Z0-9._]+$">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="twitter">
                                <i class="fab fa-twitter"></i> Twitter:
                            </label>
                            <input type="text" 
                                   id="twitter" 
                                   name="twitter" 
                                   placeholder="@usuario_twitter"
                                   pattern="^@?[a-zA-Z0-9_]+$">
                        </div>
                        <div class="col">
                            <label for="tiktok">
                                <i class="fab fa-tiktok"></i> TikTok:
                            </label>
                            <input type="text" 
                                   id="tiktok" 
                                   name="tiktok" 
                                   placeholder="@usuario_tiktok"
                                   pattern="^@?[a-zA-Z0-9_.]+$">
                        </div>
                    </div>
                </div>

                    <div class="col">
                        <label for="descripcion">
                            <i class="fas fa-file-alt"></i> Breve Descripción:
                        </label>
                        <textarea id="descripcion" name="DescripcionUsuario" rows="3" required aria-hidden="true" style="visibility: hidden;"> </textarea>
                    </div>

                    <button class="boton-mobile" type="submit">Registrarse</button>

                    <div class="mobile-back-to-login mobile-only">
                        <button type="button" class="toggle-button" onclick="toggleForm()">Iniciar Sesión</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel de Información para Sign In -->
        <div class="info-container info-signin">
            <h2>OrientaPro</h2>
            <p><i>¡Ingresa y descubre tu camino hacia el éxito profesional!</i></p>
            <h5>No te lo puedes perder</h5>
            <button type="button" class="toggle-button" onclick="toggleForm()">Crear cuenta</button>
        </div>

        <!-- Panel de Información para Sign Up -->
        <div class="info-container info-signup">
            <h2>¡Únete a OrientaPro!</h2>
            <p><i>Y descubre el poder de elegir el camino perfecto para ti.</i></p>
            <h5>Te esperamos</h5>
            <button type="button" class="toggle-button" onclick="toggleForm()">Iniciar Sesión</button>
        </div>
    </div>

    <script>
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


    </script>
</body>
</html>