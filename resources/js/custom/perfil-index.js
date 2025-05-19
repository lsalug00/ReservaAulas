// --- Email ---
const editarBtn = document.getElementById('btn-editar-correo');
const guardarBtn = document.getElementById('btn-guardar-correo');
const cancelarBtn = document.getElementById('btn-cancelar-correo');
const emailActual = document.getElementById('correo-actual');
const emailEdicion = document.getElementById('correo-edicion');
const emailInput = document.getElementById('nuevo-correo');
const errorMsg = document.getElementById('correo-error');

editarBtn.addEventListener('click', () => {
    emailActual.style.display = 'none';
    emailEdicion.classList.remove('hidden');
});

cancelarBtn.addEventListener('click', () => {
    emailEdicion.classList.add('hidden');
    emailActual.style.display = 'inline';
    errorMsg.classList.add('hidden');
});

guardarBtn.addEventListener('click', () => {
    fetch('/perfil/email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ email: emailInput.value })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            emailActual.textContent = emailInput.value;
            cancelarBtn.click();
        } else {
            errorMsg.textContent = data.message || 'Error al actualizar.';
            errorMsg.classList.remove('hidden');
        }
    })
    .catch(() => {
        errorMsg.textContent = 'Error inesperado.';
        errorMsg.classList.remove('hidden');
    });
});

// --- Nombre y Apellido ---
const regexNombre = /^(Mª|[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)(\s?(del|de|la|los)?\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*$/;
const regexApellido = /^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(?:\s(de((\s)(la|los))?|del\s)?[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*(?:\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?(?:-[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?$/;

function setupEdicion(campo) {
    const btnEditar = document.getElementById(`btn-editar-${campo}`);
    const btnGuardar = document.getElementById(`btn-guardar-${campo}`);
    const btnCancelar = document.getElementById(`btn-cancelar-${campo}`);
    const spanActual = document.getElementById(`${campo}-actual`);
    const divEdicion = document.getElementById(`${campo}-edicion`);
    const input = document.getElementById(`nuevo-${campo}`);
    const error = document.getElementById(`${campo}-error`);

    btnEditar.addEventListener('click', () => {
        spanActual.style.display = 'none';
        btnEditar.style.display = 'none';
        divEdicion.classList.remove('hidden');
        error.classList.add('hidden');
    });

    btnCancelar.addEventListener('click', () => {
        divEdicion.classList.add('hidden');
        spanActual.style.display = 'inline';
        btnEditar.style.display = 'inline-block';
        error.classList.add('hidden');
        input.value = spanActual.textContent.trim();
    });

    btnGuardar.addEventListener('click', () => {
        const valor = input.value.trim();
        const esValido = campo === 'nombre' ? regexNombre.test(valor) : regexApellido.test(valor);

        if (!esValido) {
            error.textContent = `Formato de ${campo} inválido.`;
            error.classList.remove('hidden');
            return;
        }

        fetch(`/perfil/${campo}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ [campo]: valor })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                spanActual.textContent = valor;
                btnCancelar.click();
            } else {
                error.textContent = data.message || 'Error al actualizar.';
                error.classList.remove('hidden');
            }
        })
        .catch(() => {
            error.textContent = 'Error inesperado.';
            error.classList.remove('hidden');
        });
    });
}

setupEdicion('nombre');
setupEdicion('apellido');

if (document.getElementById('cancelar-modal')) {
    import('./cancelar-reserva.js');
}