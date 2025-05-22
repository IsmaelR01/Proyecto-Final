document.addEventListener('DOMContentLoaded', function () {
    
    const formularioEliminar = document.getElementById('formularioEliminar');

    const mensajeEliminar = document.getElementById('mensajeEliminar');

    if (formularioEliminar && mensajeEliminar) {
        formularioEliminar.addEventListener('submit', function (e) {
            if (seleccionUsuario && seleccionUsuario.value === "") {
                e.preventDefault();
                mensajeEliminar.innerHTML = "Debes seleccionar un usuario válido para eliminar.";
            }
        });
    }
});
