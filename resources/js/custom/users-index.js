// Función para obtener nombre desde el contenedor, tabla o móvil
function obtenerNombreUsuario(container) {
    if (!container) return '(usuario)';

    // Tabla
    const fila = container.closest('tr');
    if (fila) {
        const tdNombre = fila.querySelector('td:nth-child(2)');
        const tdApellidos = fila.querySelector('td:nth-child(3)');
        const nombre = tdNombre?.textContent.trim() ?? '';
        const apellidos = tdApellidos?.textContent.trim() ?? '';
        return `${nombre} ${apellidos}`.trim() || '(usuario)';
    }

    // Móvil (card-body)
    const cardBody = container.closest('.card-body');
    if (cardBody) {
        let nombre = '', apellidos = '';
        const divs = cardBody.querySelectorAll('div');
        divs.forEach(div => {
            const strong = div.querySelector('strong');
            if (!strong) return;
            const label = strong.textContent.trim();
            if (label === 'Nombre:') {
                nombre = div.textContent.replace('Nombre:', '').trim();
            } else if (label === 'Apellidos:') {
                apellidos = div.textContent.replace('Apellidos:', '').trim();
            }
        });
        return `${nombre} ${apellidos}`.trim() || '(usuario)';
    }

    return '(usuario)';
}


// Funcionalidad cerrar alertas
document.querySelectorAll('.close-alert').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.alert').remove());
});

// Código
document.querySelectorAll('.codigo-container').forEach(container => {
    const editarBtn = container.querySelector('.editar-btn');
    const form = container.querySelector('.codigo-form');
    const cancelarBtn = container.querySelector('.cancelar-btn');
    const guardarBtn = container.querySelector('.guardar-btn');
    const input = container.querySelector('.codigo-input');
    const textoSpan = container.querySelector('.codigo-text');

    editarBtn.addEventListener('click', () => {
        form.classList.remove('hidden');
        editarBtn.classList.add('hidden');
        textoSpan.classList.add('hidden');
    });

    cancelarBtn.addEventListener('click', () => {
        form.classList.add('hidden');
        editarBtn.classList.remove('hidden');
        textoSpan.classList.remove('hidden');
        input.value = textoSpan.textContent.trim();
        guardarBtn.disabled = true;
    });

    input.addEventListener('input', () => {
        const valido = /^[A-Z]{2}[0-9]{2}$/.test(input.value);
        guardarBtn.disabled = !valido || input.value === textoSpan.textContent.trim();
    });

    guardarBtn.addEventListener('click', () => {
        const modal = document.getElementById('modalCodigo');
        // Poner el nombre en el modal
        const nombreSpan = modal.querySelector('#modalCodigoNombre');
        nombreSpan.textContent = obtenerNombreUsuario(container);
        modal.classList.add('modal-open');

        const confirmarBtn = document.getElementById('confirmarCodigo');
        const cancelarModalBtn = document.getElementById('cancelarCodigo');

        function confirmarHandler() {
            modal.classList.remove('modal-open');
            form.submit();
            limpiar();
        }
        function cancelarHandler() {
            modal.classList.remove('modal-open');
            limpiar();
        }
        function limpiar() {
            confirmarBtn.removeEventListener('click', confirmarHandler);
            cancelarModalBtn.removeEventListener('click', cancelarHandler);
        }
        confirmarBtn.addEventListener('click', confirmarHandler);
        cancelarModalBtn.addEventListener('click', cancelarHandler);
    });
});

// Rol
document.querySelectorAll('.rol-container').forEach(container => {
    const editarBtn = container.querySelector('.editar-rol-btn');
    const form = container.querySelector('.rol-form');
    const cancelarBtn = container.querySelector('.cancelar-rol-btn');
    const guardarBtn = container.querySelector('.guardar-rol-btn');
    const select = container.querySelector('select');
    const textoSpan = container.querySelector('.rol-text');

    editarBtn.addEventListener('click', () => {
        form.classList.remove('hidden');
        editarBtn.classList.add('hidden');
        textoSpan.classList.add('hidden');
    });

    cancelarBtn.addEventListener('click', () => {
        form.classList.add('hidden');
        editarBtn.classList.remove('hidden');
        textoSpan.classList.remove('hidden');
        select.value = textoSpan.textContent.trim().toLowerCase();
        guardarBtn.disabled = true;
    });

    select.addEventListener('change', () => {
        guardarBtn.disabled = select.value === textoSpan.textContent.trim().toLowerCase();
    });

    guardarBtn.addEventListener('click', () => {
        const modal = document.getElementById('modalRol');
        // Poner el nombre en el modal
        const nombreSpan = modal.querySelector('#modalRolNombre');
        nombreSpan.textContent = obtenerNombreUsuario(container);
        modal.classList.add('modal-open');

        const confirmarBtn = document.getElementById('confirmarRol');
        const cancelarModalBtn = document.getElementById('cancelarRol');

        function confirmarHandler() {
            modal.classList.remove('modal-open');
            form.submit();
            limpiar();
        }
        function cancelarHandler() {
            modal.classList.remove('modal-open');
            limpiar();
        }
        function limpiar() {
            confirmarBtn.removeEventListener('click', confirmarHandler);
            cancelarModalBtn.removeEventListener('click', cancelarHandler);
        }
        confirmarBtn.addEventListener('click', confirmarHandler);
        cancelarModalBtn.addEventListener('click', cancelarHandler);
    });
});

// Activar / Desactivar
document.querySelectorAll('.activo-container').forEach(container => {
    const form = container.querySelector('.toggle-form');
    const btn = container.querySelector('.toggle-btn');

    btn.addEventListener('click', () => {
        const modal = document.getElementById('modalActivo');
        modal.classList.add('modal-open');

        // Obtener el nombre completo reutilizando la función
        const nombreCompleto = obtenerNombreUsuario(container);

        // Poner la acción (activar/desactivar) y el nombre en el modal
        modal.querySelector('#accionActivo').textContent = btn.textContent.trim().toLowerCase();
        modal.querySelector('#modalActivoNombre').textContent = nombreCompleto;

        const confirmarBtn = document.getElementById('confirmarActivo');
        const cancelarBtn = document.getElementById('cancelarActivo');

        function confirmarHandler() {
            modal.classList.remove('modal-open');
            form.submit();
            limpiar();
        }

        function cancelarHandler() {
            modal.classList.remove('modal-open');
            limpiar();
        }

        function limpiar() {
            confirmarBtn.removeEventListener('click', confirmarHandler);
            cancelarBtn.removeEventListener('click', cancelarHandler);
        }

        confirmarBtn.addEventListener('click', confirmarHandler);
        cancelarBtn.addEventListener('click', cancelarHandler);
    });
});

// Eliminar
document.querySelectorAll('.eliminar-container').forEach(container => {
    const form = container.querySelector('form');
    const btn = container.querySelector('.eliminar-btn');

    btn.addEventListener('click', () => {
        const modal = document.getElementById('modalEliminar');
        modal.classList.add('modal-open');

        const nombre = obtenerNombreUsuario(container);
        modal.querySelector('#modalEliminarNombre').textContent = nombre;

        const confirmarBtn = document.getElementById('confirmarEliminar');
        const cancelarBtn = document.getElementById('cancelarEliminar');

        function confirmarHandler() {
            modal.classList.remove('modal-open');
            form.submit();
            limpiar();
        }

        function cancelarHandler() {
            modal.classList.remove('modal-open');
            limpiar();
        }

        function limpiar() {
            confirmarBtn.removeEventListener('click', confirmarHandler);
            cancelarBtn.removeEventListener('click', cancelarHandler);
        }

        confirmarBtn.addEventListener('click', confirmarHandler);
        cancelarBtn.addEventListener('click', cancelarHandler);
    });
});