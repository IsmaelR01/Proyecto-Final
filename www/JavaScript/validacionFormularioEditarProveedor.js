window.addEventListener('load', iniciarEditarProveedor, false);

function iniciarEditarProveedor() {
    const enviar = document.getElementById('botonEditarEnviar'); 
    enviar.addEventListener('click', validarFormularioProveedor, false); 
    const cancelar = document.getElementById('botonEditarCancelar'); 
    cancelar.addEventListener('click', resetearFormularioEditarProveedor, false); 
    const cerrar = document.getElementById('botonEditarCerrar');
    cerrar.addEventListener('click', resetearFormularioEditarProveedor, false); 
}

function validarFormularioProveedor(evento) {
    const resultado = document.getElementById('resultadoEditarProveedor');
    resultado.innerHTML = ""; 

    let nombre = validarEditarNombre();
    let direccion = validarEditarDireccion();
    let telefono = validarEditarTelefono();

    let formularioValido = true;

    if(!(nombre && direccion && telefono)) {
        evento.preventDefault();
        formularioValido = false
    }

    return formularioValido;
}

function validarEditarNombre() {
    const nombre = document.getElementById('editarNombre_proveedor');
    const resultado = document.getElementById('resultadoEditarProveedor');
    let valido = true;


    if (nombre.value.trim() === "") {
        resultado.innerHTML += "El nombre no puede estar vacío.<br>";
        nombre.className = "form-control error";
        nombre.focus();
        valido = false;
    } else if (nombre.value.length > 40) {
        resultado.innerHTML += "El nombre no puede superar los 40 caracteres.<br>";
        nombre.className = "form-control error";
        valido = false;
    } else {
        nombre.className = "form-control exito";
    }

    return valido;
}

function validarEditarDireccion() {
    let direccion = document.getElementById('editarDireccion_proveedor');
    let resultado = document.getElementById('resultadoEditarProveedor');
    let devolver = true;

    if (direccion === "") {
        resultado.innerHTML += "La direccion no puede estar vacía.<br>";
        direccion.focus();
        direccion.className = "form-control error";
        devolver = false;
    } else if (!/^[a-zA-Z0-9áéíóúÁÉÍÓÚüÜñÑ\s,\.]+(\d{1,5})?(\s?[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]+)?(\s?\d{5})?(\s?[a-zA-Z\s]+)?$/.test(direccion.value)) {
        resultado.innerHTML += "La dirección no cumple con los requisitos.<br>";
        direccion.className = "form-control error";
        devolver = false;
    } else {
        direccion.className = "form-control exito";
    }
    return devolver;
}

function validarEditarTelefono() {
    let telefono = document.getElementById('editarTelefono');
    let resultado = document.getElementById('resultadoEditarProveedor');
    let devolver = true;

    if (telefono === "") {
        resultado.innerHTML += "El teléfono no puede estar vacío.<br>";
        telefono.focus();
        telefono.className = "form-control error";
        devolver = false;
    } else if (!/^[6789][0-9]{8}$/.test(telefono.value)) {
        resultado.innerHTML += "El teléfono no cumple con los requisitos.<br>";
        telefono.className = "form-control error";
        devolver = false;
    } else {
        telefono.className = "form-control exito";
    }
    return devolver;
}

function resetearFormularioEditarProveedor() {
    const resultado = document.getElementById('resultadoEditarProveedor');
    const inputs = document.getElementsByClassName('form-control');
    
    resultado.innerHTML = "";

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
    }
}