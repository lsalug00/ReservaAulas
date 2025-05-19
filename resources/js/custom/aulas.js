let aulaIdToDelete = null;

const modal = document.getElementById('delete-modal');
const modalNombre = document.getElementById('modal-aula-nombre');

const btnCancelar = document.getElementById('btn-cancelar');
const btnEliminarConfirmar = document.getElementById('btn-eliminar-confirmar');

function openDeleteModal(id, nombre) {
aulaIdToDelete = id;
modalNombre.textContent = nombre;
modal.classList.add('modal-open');
}

function closeDeleteModal() {
aulaIdToDelete = null;
modal.classList.remove('modal-open');
}

function submitDeleteForm() {
if (!aulaIdToDelete) return;
const form = document.getElementById(`delete-form-${aulaIdToDelete}`);
if (form) form.submit();
}

document.querySelectorAll('.btn-eliminar').forEach(button => {
button.addEventListener('click', () => {
    const id = button.dataset.id;
    const nombre = button.dataset.nombre;
    openDeleteModal(id, nombre);
});
});

btnCancelar.addEventListener('click', closeDeleteModal);
btnEliminarConfirmar.addEventListener('click', submitDeleteForm);