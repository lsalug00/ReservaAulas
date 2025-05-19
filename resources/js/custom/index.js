const todasLasAulas = window.todasLasAulas || [];
const categoriaSelect = document.getElementById('categoria_id');
const aulaSelect = document.getElementById('aula_id');
const edificioInput = document.getElementById('edificio');
const plantaInput = document.getElementById('planta');
const capacidadInput = document.getElementById('capacidad');
const mensajeSinAulas = document.getElementById('mensaje-sin-aulas');
const botonBuscar = document.getElementById('boton-buscar');

const horaInicioInput = document.getElementById('hora_inicio');
const horaFinInput = document.getElementById('hora_fin');
const mensajeHoraError = document.getElementById('mensaje-hora-error');  // Aquí se muestra el mensaje de error de horas
const botonReservar = document.getElementById('boton-reservar');

const aulaSeleccionada = window.aulaSeleccionada || '';

function filtrarAulas() {
    const categoria = categoriaSelect.value;
    const edificio = edificioInput?.value;
    const planta = plantaInput?.value;
    const capacidadMin = parseInt(capacidadInput?.value);

    // Limpiar el select de aulas
    while (aulaSelect.firstChild) {
        aulaSelect.removeChild(aulaSelect.firstChild);
    }

    // Agregar opción por defecto manualmente
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Selecciona un aula';
    aulaSelect.appendChild(defaultOption);
    mensajeSinAulas?.classList.add('hidden');

    const opcionesFiltradas = todasLasAulas.filter(aula => {
        const coincideCategoria = !categoria || aula.categoria_id == categoria;
        const coincideEdificio = !edificio || aula.edificio === edificio;
        const coincidePlanta = !planta || aula.planta === planta;
        const coincideCapacidad = !capacidadMin || aula.capacidad >= capacidadMin;
        return coincideCategoria && coincideEdificio && coincidePlanta && coincideCapacidad;
    });

    opcionesFiltradas.forEach(aula => {
        const option = document.createElement('option');
        option.value = aula.id;
        option.textContent = `${aula.codigo} - ${aula.nombre}`;
        if (aula.id == aulaSeleccionada) option.selected = true;
        aulaSelect.appendChild(option);
    });

    if (opcionesFiltradas.length === 0) {
        mensajeSinAulas?.classList.remove('hidden');
        mensajeSinAulas.textContent = 'No hay aulas que coincidan con los filtros';
        botonBuscar?.setAttribute('disabled', 'disabled');
    } else {
        mensajeSinAulas?.classList.add('hidden');
        botonBuscar?.removeAttribute('disabled');
    }
}

function validarHoras() {
    const horaInicio = horaInicioInput.value;
    const horaFin = horaFinInput.value;

    // Convertimos las horas a objetos Date para poder compararlas
    const startTime = new Date(`1970-01-01T${horaInicio}:00`);
    const endTime = new Date(`1970-01-01T${horaFin}:00`);

    // Si la hora de fin es anterior o igual a la de inicio
    if (endTime <= startTime) {
        // Mostrar mensaje de error
        mensajeHoraError?.classList.remove('hidden');
        mensajeHoraError.textContent = 'La hora de fin debe ser posterior a la hora de inicio.';
        botonReservar?.setAttribute('disabled', 'disabled');
    } else {
        // Si las horas son válidas, ocultar el mensaje de error
        mensajeHoraError?.classList.add('hidden');
        botonReservar?.removeAttribute('disabled');
    }
}

capacidadInput?.addEventListener('input', filtrarAulas);

[categoriaSelect, edificioInput, plantaInput, capacidadInput].forEach(input => {
    input?.addEventListener('change', filtrarAulas);
});

horaInicioInput?.addEventListener('change', validarHoras);
horaFinInput?.addEventListener('change', validarHoras);

filtrarAulas(); // Ejecutar al cargar

document.querySelectorAll('.close-alert').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.alert').remove();
    });
});