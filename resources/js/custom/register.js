// Expresiones regulares
const regexNombre = /^(Mª|[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)(\s?(del|de|la|los)?\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*$/;
const regexApellido = /^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(?:\s(de((\s)(la|los))?|del\s)?\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*(?:\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?(?:-[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?$/;

// Validación en tiempo real
function validarInput(input, regex, min, max) {
    input.addEventListener('input', () => {
        const valor = input.value.trim();
        const esValido = regex.test(valor) && valor.length >= min && valor.length <= max;
        input.classList.toggle('input-error', !esValido);
        input.classList.toggle('input-success', esValido);
    });
}

const nombreInput = document.getElementById('name');
const apellidoInput = document.getElementById('surname');

if (nombreInput && apellidoInput) {
    validarInput(nombreInput, regexNombre, 3, 20);
    validarInput(apellidoInput, regexApellido, 3, 50);
}

// Mostrar/ocultar contraseña
function togglePasswordVisibility(toggleId, inputId, openId, closedId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);
    const eyeOpen = document.getElementById(openId);
    const eyeClosed = document.getElementById(closedId);
    if (toggle && input) {
        toggle.addEventListener('click', () => {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        });
    }
}

togglePasswordVisibility('togglePassword', 'password', 'eyeOpen', 'eyeClosed');
togglePasswordVisibility('togglePasswordConfirm', 'password_confirmation', 'eyeOpenConfirm', 'eyeClosedConfirm');