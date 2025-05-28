window.addEventListener('load', iniciarEditarCuenta, false);

function iniciarEditarCuenta() {
    const formulario = document.getElementById('formularioEditarCuenta');
    if (formulario) {
        formulario.addEventListener('submit', validarFormularioEditarCuenta, false);
    }
}

function validarFormularioEditarCuenta(evento) {
    document.getElementById('resultadoEditarCuenta').innerHTML = ""; // Limpiar mensajes

    let nombreValido = validarEditarNombreUsuario();
    let direccionValida = validarEditarDireccion();
    let imagenValida = validarEditarImagen();

    let contrasenaAntigua = document.getElementById('contrasena_antigua').value.trim();
    let contrasenaNueva = document.getElementById('contrasena_nueva').value.trim();

    let contrasenaValida = true;

    let formularioValido = true;

    if (contrasenaAntigua !== "" || contrasenaNueva !== "") {
        contrasenaValida = validarContrasenaAntigua() && validarContrasenaNueva();
    }

    if (!(nombreValido && direccionValida && imagenValida && contrasenaValida)) {
        evento.preventDefault();
        formularioValido = false;
    }

    return formularioValido;
}


function validarEditarNombreUsuario() {
    let nombreUsuario = document.getElementById('editarNombreUsuario');
    let resultado = document.getElementById('resultadoEditarCuenta');
    let devolver = true;

    if (nombreUsuario.value === "") {
        resultado.innerHTML += "El nombre de usuario no puede estar vacío.<br>";
        nombreUsuario.focus();
        nombreUsuario.className = "form-control error";
        devolver = false;
    } else if (!/^[A-Za-z][a-z0-9]{0,11}$/.test(nombreUsuario.value)) {
        resultado.innerHTML += "El nombre de usuario no cumple con los requisitos.<br>";
        nombreUsuario.className = "form-control error";
        devolver = false;
    } else {
        nombreUsuario.className = "form-control exito";
    }
    return devolver;
}


function validarEditarDireccion() {
    let direccion = document.getElementById('editarDireccion');
    let resultado = document.getElementById('resultadoEditarCuenta');
    let devolver = true;

    if (direccion.value === "") {
        resultado.innerHTML += "La direccion no puede estar vacía.<br>";
        direccion.focus();
        direccion.className = "form-control error";
        devolver = false;
    } else if (!/^(Calle|Avenida|Plaza)\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+,\s*\d{1,4},\s*\d{5}\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+(\s*\([a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+\))?$/.test(direccion.value)) {
        resultado.innerHTML += "La dirección no cumple con los requisitos.<br>";
        direccion.className = "form-control error";
        devolver = false;
    } else {
        direccion.className = "form-control exito";
    }
    return devolver;
}

function validarEditarImagen() {
    const imagen = document.getElementById('editarPerfil');
    const resultado = document.getElementById('resultadoEditarCuenta');
    let valido = true;


    if (imagen && imagen.files.length > 0) {
        const archivo = imagen.files[0].name.toLowerCase();
        if (!archivo.match(/\.(jpg|jpeg|png)$/)) {
            resultado.innerHTML += "La imagen debe ser .jpg, .jpeg o .png<br>";
            imagen.className = "form-control error";
            valido = false;
        } else {
            imagen.className = "form-control exito";
        }
    } 

    return valido;
}

function validarContrasenaAntigua() {
    let contrasenaAntigua = document.getElementById('contrasena_antigua');
    let resultado = document.getElementById('resultadoEditarCuenta');
    let devolver = true;

    if (contrasenaAntigua.value.trim() === "") {
        resultado.innerHTML += "Debe introducir la contraseña actual si desea cambiarla.<br>";
        contrasenaAntigua.focus();
        contrasenaAntigua.className = "form-control error";
        devolver = false;
    } else if (!/^[A-Z][a-z0-9]*[.]?[a-z0-9]*$/.test(contrasenaAntigua.value)) {
        resultado.innerHTML += "La contraseña actual no cumple con los requisitos.<br>";
        contrasenaAntigua.className = "form-control error";
        devolver = false;
    } else {
        contrasenaAntigua.className = "form-control exito";
    }
    return devolver;
}

function validarContrasenaNueva() {
    let contrasenaNueva = document.getElementById('contrasena_nueva');
    let resultado = document.getElementById('resultadoEditarCuenta');
    let devolver = true;

    if (contrasenaNueva.value.trim() === "") {
        resultado.innerHTML += "Debe introducir una nueva contraseña si desea cambiarla.<br>";
        contrasenaNueva.focus();
        contrasenaNueva.className = "form-control error";
        devolver = false;
    } else if (!/^[A-Z][a-z0-9]*[.]?[a-z0-9]*$/.test(contrasenaNueva.value)) {
        resultado.innerHTML += "La nueva contraseña no cumple con los requisitos.<br>";
        contrasenaNueva.className = "form-control error";
        devolver = false;
    } else {
        contrasenaNueva.className = "form-control exito";
    }
    return devolver;
}

function resetearFormularioEditarCuenta() {
    const resultado = document.getElementById('resultadoEditarCuenta');
    resultado.innerHTML = "";

    const inputs = document.getElementsByClassName('form-control');

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
    }
}

