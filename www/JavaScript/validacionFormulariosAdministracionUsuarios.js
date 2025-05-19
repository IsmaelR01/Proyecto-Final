document.addEventListener('DOMContentLoaded', function () {
    const accionSelect = document.getElementById('accion');
    const formularioAccion = document.getElementById('formularioAccion');
    const mensajeAccion = document.getElementById('mensajeAccion');

    if (accionSelect && formularioAccion) {
        accionSelect.addEventListener('change', function () {
            if (accionSelect.value === "") {
                mensajeAccion.innerHTML = "Por favor, selecciona una acción válida.";
            } else {
                mensajeAccion.innerHTML = "";
                formularioAccion.submit();
            }
        });
    }

    const seleccionUsuario = document.getElementById('seleccionUsuario');
    const formularioUsuario = document.getElementById('formularioUsuario');
    const formularioEditar = document.getElementById('formularioEditar');
    const formularioEliminar = document.getElementById('formularioEliminar');

    const mensajeEditar = document.getElementById('mensajeEditar');
    const mensajeEliminar = document.getElementById('mensajeEliminar');

    if (seleccionUsuario && formularioUsuario) {
        seleccionUsuario.addEventListener('change', function () {
            if (seleccionUsuario.value === "") {
                if (mensajeEditar) mensajeEditar.innerHTML = "Selecciona un usuario para editar.";
                if (mensajeEliminar) mensajeEliminar.innerHTML = "Selecciona un usuario para eliminar.";
            } else {
                if (mensajeEditar) mensajeEditar.innerHTML = "";
                if (mensajeEliminar) mensajeEliminar.innerHTML = "";
                formularioUsuario.submit();
            }
        });
    }

    if (formularioEditar && mensajeEditar) {
        formularioEditar.addEventListener('submit', function (e) {
            if (seleccionUsuario && seleccionUsuario.value === "") {
                e.preventDefault();
                mensajeEditar.innerHTML = "Debes seleccionar un usuario válido para editar.";
            }
        });
    }

    if (formularioEliminar && mensajeEliminar) {
        formularioEliminar.addEventListener('submit', function (e) {
            if (seleccionUsuario && seleccionUsuario.value === "") {
                e.preventDefault();
                mensajeEliminar.innerHTML = "Debes seleccionar un usuario válido para eliminar.";
            }
        });
    }
});
