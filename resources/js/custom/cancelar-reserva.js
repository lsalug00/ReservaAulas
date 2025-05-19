let reservaId = null;

document.querySelectorAll('.abrir-modal').forEach(btn => {
    btn.addEventListener('click', function () {
        reservaId = this.getAttribute('data-id');
    });
});

document.getElementById('confirmar-cancelacion').addEventListener('click', function () {
    if (reservaId) {
        document.getElementById('form-cancelar-' + reservaId).submit();
    }
});