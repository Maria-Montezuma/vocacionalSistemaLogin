<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="body-password">
    <div class="form-wrapper">
        <div class="container">
            <div class="form">
                <h2>Recuperar Contraseña</h2>
                
                <!-- Paso 1: Selección del método de recuperación -->
                <div class="step active-step" id="step-1">
                    <h3>¿Cómo deseas recuperar tu contraseña?</h3>
                    <label>
                        <input type="radio" name="recovery-method" value="email" onclick="showStep(2)" required> 
                        Recuperar por correo electrónico
                    </label>
                    <br>
                    <label>
                        <input type="radio" name="recovery-method" value="questions" onclick="showStep(4)" required> 
                        Recuperar por preguntas secretas
                    </label>

                    <button type="button" onclick="window.location.href='registro'" class="login-button">
                        Regresar al Login
                    </button>
                </div>
                
                <!-- Paso 2: Recuperación por correo electrónico -->
                <div class="step" id="step-2">
                    <h3>Ingresa tu correo electrónico</h3>
                    <div class="col">
                        <label for="email-recovery">
                            <i class="fas fa-envelope"></i> Correo Electrónico:
                        </label>
                        <input type="email" id="email-recovery" name="email" required>
                    </div>
                    <button type="submit">Enviar enlace de recuperación</button>
                    <button type="button" onclick="showStep(1)">Volver a elegir método</button>
                </div>
                
                <!-- Paso 3: Solicitud de correo antes de preguntas secretas -->
                <div class="step" id="step-4">
                    <h3>Primero ingresa tu correo electrónico</h3>
                    <div class="col">
                        <label for="email-security">
                            <i class="fas fa-envelope"></i> Correo Electrónico:
                        </label>
                        <input type="email" id="email-security" name="email" required>
                    </div>
                    <button type="button" onclick="validateEmail()">Continuar</button>
                    <button type="button" onclick="showStep(1)">Volver a elegir método</button>
                </div>
                
                <!-- Paso 4: Recuperación por preguntas secretas -->
                <div class="step" id="step-3">
                    <h3>Responde tus preguntas de seguridad</h3>
                    <div class="col">
                        <label for="security-question-1">
                            <i class="fas fa-shield-alt"></i> Pregunta de seguridad #1:
                        </label>
                        <select id="security-question-1" name="security_question_1" class="security-select" required>
                            <option value="">Selecciona una pregunta</option>
                            <option value="mascota">¿Cuál es el nombre de tu primera mascota?</option>
                            <option value="ciudad">¿En qué ciudad naciste?</option>
                            <option value="escuela">¿Cuál fue tu primera escuela?</option>
                        </select>
                        <input type="text" id="answer1" name="answer1" placeholder="Tu respuesta" required>
                    </div>
                    
                    <button type="submit">Verificar respuestas</button>
                    <button type="button" onclick="showStep(1)">Volver a elegir método</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showStep(stepNumber) {
            const steps = document.querySelectorAll('.step');
            steps.forEach(step => {
                step.classList.remove('active-step');
            });
            document.getElementById('step-' + stepNumber).classList.add('active-step');
        }

        function validateEmail() {
            const emailInput = document.getElementById('email-security');
            if (emailInput.value.trim() === "") {
                alert("Por favor, ingresa un correo válido.");
            } else {
                showStep(3);
            }
        }
    </script>
</body>
</html>