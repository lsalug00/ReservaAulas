const eventos = window.eventosData || [];
const diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];

const fechaInicio = new Date(window.fechaInicio);// septiembre
const fechaFin = new Date(window.fechaFin);// junio siguiente
let fechaReferencia = new Date(window.fechaInicio);// arranca en septiembre
let fechaSeleccionada = null;

const container = document.getElementById('calendar-grid');
const mesActual = document.getElementById('mes-actual');
const btnPrev = document.getElementById('prev');
const btnNext = document.getElementById('next');
const modal = document.getElementById('modal-agregar');
const modalFecha = document.getElementById('modal-fecha');
const modalDescripcion = document.getElementById('modal-descripcion');
const btnGuardar = document.getElementById('btn-guardar-dia');
const btnCancelar = document.getElementById('btn-cancelar-dia');

const render = () => {
    container.innerHTML = '';
    mesActual.textContent = fechaReferencia.toLocaleDateString('es-ES', {
        month: 'long',
        year: 'numeric'
    });

    diasSemana.forEach(dia => {
        const cell = document.createElement('div');
        cell.className = 'font-bold';
        cell.textContent = dia;
        container.appendChild(cell);
    });

    const year = fechaReferencia.getFullYear();
    const month = fechaReferencia.getMonth();
    const primerDiaMes = new Date(year, month, 1);

    let inicio = primerDiaMes.getDay();
    inicio = inicio === 0 ? 6 : inicio - 1; // lunes como primer día

    for (let i = 0; i < inicio; i++) {
        container.appendChild(document.createElement('div'));
    }

    const diasEnMes = new Date(year, month + 1, 0).getDate();

    for (let dia = 1; dia <= diasEnMes; dia++) {
        const fechaActual = new Date(year, month, dia);
        const fechaISO = fechaActual.getFullYear() + '-' +
            String(fechaActual.getMonth() + 1).padStart(2, '0') + '-' +
            String(fechaActual.getDate()).padStart(2, '0'); // YYYY-MM-DD
        
        const diaSemana = fechaActual.getDay(); // 0 (Dom) - 6 (Sáb)
        const esFinde = diaSemana === 0 || diaSemana === 6;

        const evento = eventos.find(e => e.start === fechaISO);

        const cell = document.createElement('div');
        cell.className = 'p-2 rounded relative text-center whitespace-nowrap min-w-[2rem]';

        if (evento) {
            if (evento.esManual) {
                cell.classList.add('bg-success/80', 'hover:brightness-90');
            } else if (evento.es_finde) {
                cell.classList.add('bg-warning/80', 'hover:brightness-90');
            } else {
                cell.classList.add('bg-error/80', 'hover:brightness-90');
            }
            cell.addEventListener('click', () => {
                const contenedor = document.getElementById('descripcion-dia');
                const fechaFormateada = fechaActual.toLocaleDateString('es-ES');
                contenedor.textContent = `${fechaFormateada}: ${evento.title}`;
                contenedor.classList.remove('hidden');
            });
        } else {
            if (!esFinde) {
                cell.classList.add('hover:bg-primary/50');
                cell.addEventListener('click', () => {
                    fechaSeleccionada = fechaISO;
                    modalFecha.textContent = `Fecha: ${fechaActual.toLocaleDateString('es-ES')}`;
                    modalDescripcion.value = '';
                    modal.showModal();
                });
            } else {
                cell.classList.add('text-gray-400');
            }
        }

        cell.textContent = dia;
        container.appendChild(cell);
    }

    // Deshabilitar navegación fuera del rango septiembre-junio
    btnPrev.disabled = (
        fechaReferencia.getFullYear() === fechaInicio.getFullYear() &&
        fechaReferencia.getMonth() === fechaInicio.getMonth()
    );
    btnNext.disabled = (
        fechaReferencia.getFullYear() === fechaFin.getFullYear() &&
        fechaReferencia.getMonth() === fechaFin.getMonth()
    );
};

btnPrev.addEventListener('click', () => {
    fechaReferencia.setMonth(fechaReferencia.getMonth() - 1);
    render();
});

btnNext.addEventListener('click', () => {
    fechaReferencia.setMonth(fechaReferencia.getMonth() + 1);
    render();
});

btnCancelar.addEventListener('click', () => {
    modal.close();
});

btnGuardar.addEventListener('click', () => {
    const descripcion = modalDescripcion.value.trim();
    if (!descripcion) return;

    eventos.push({
        start: fechaSeleccionada,
        title: descripcion,
        es_finde: false,
        esManual: true
    });

    modal.close();
    render();
});

document.getElementById('btn-guardar-todos').addEventListener('click', () => {
    const input = document.getElementById('input-eventos');
    input.value = JSON.stringify(eventos);
    document.getElementById('form-guardar-dias').submit();
});

render();