document.addEventListener('DOMContentLoaded', function() {
    // Botón de alternar preguntas
    const toggleButton = document.getElementById('toggle-questions');
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            const questionsSection = document.getElementById('questions-section');
            if (questionsSection) {
                questionsSection.style.display = questionsSection.style.display === 'none' ? 'block' : 'none';
            }
        });
    }

    // Seleccionar todos los select de preguntas
    const selects = document.querySelectorAll('.question-select');
    
    function validateQuestions() {
        const selectedValues = new Set();
        let isValid = true;
        
        // Limpiar mensajes de error previos
        document.querySelectorAll('.error-message').forEach(error => {
            error.style.display = 'none';
            error.textContent = '';
        });

        // Validar selecciones
        selects.forEach((select, index) => {
            if (select.value) {
                if (selectedValues.has(select.value)) {
                    const errorElement = document.getElementById(`error-pregunta${index + 1}`);
                    if (errorElement) {
                        errorElement.textContent = 'Por favor seleccione preguntas diferentes';
                        errorElement.style.display = 'block';
                        isValid = false;
                    }
                }
                selectedValues.add(select.value);
            }
        });

        return isValid;
    }

    // Evento submit del formulario
    const form = document.getElementById('security-questions-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateQuestions()) {
                e.preventDefault();
            }
        });
    }

    // Eventos change de los selects
    selects.forEach(select => {
        select.addEventListener('change', validateQuestions);
    });

    document.getElementById('cancelBtn').addEventListener('click', function() {
        const questionsSection = document.getElementById('questions-section');
        questionsSection.style.display = 'none';
        
        const form = document.getElementById('security-questions-form');
        form.reset(); 
   
        document.querySelectorAll('.error-message').forEach(error => {
            error.style.display = 'none';
            error.textContent = '';
        });
        enableAllQuestions();
    });

});