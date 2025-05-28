window.addEventListener('load', iniciarAñadirProveedor, false);

function iniciarAñadirProveedor() {
    const enviar = document.getElementById('botonAñadirEnviarProveedor'); 
    enviar.addEventListener('click', validarFormularioAñadirProveedor, false); 
    const cancelar = document.getElementById('botonAñadirCancelarProveedor');
    cancelar.addEventListener('click', resetearFormularioAñadirProveedor, false); 
    const cerrar = document.getElementById('botonAñadirCerrarProveedor');
    cerrar.addEventListener('click', resetearFormularioAñadirProveedor, false); 
}

function validarFormularioAñadirProveedor(evento) {
    const resultado = document.getElementById('resultadoProveedor');
    resultado.innerHTML = ""; 

    let cifProveedor = validarAñadirCif();
    let nombre = validarAñadirNombre();
    let direccion = validarAñadirDireccion();
    let telefono = validarAñadirTelefono();

    let formularioValido = false;

    if (cifProveedor && nombre && direccion && telefono) {
        formularioValido = true;
    } else {
        evento.preventDefault();
        formularioValido = false;
    }

    return formularioValido;
}

function validarAñadirCif() {
    const cifProveedor = document.getElementById('añadirCIF');
    const resultado = document.getElementById('resultadoProveedor');
    let valido = true;

    resultado.innerHTML = ""; 

    if (cifProveedor.value.trim() === "") {
        resultado.innerHTML += "El cif no puede estar vacío.<br>";
        cifProveedor.className = "form-control error";
        cifProveedor.focus();
        valido = false;
    } else if (!/^[A-Z][0-9]{7}[A-Z0-9]?$/i.test(cifProveedor.value)) {
        resultado.innerHTML += "El cif no cumplpe con los requisitos.<br>";
        cifProveedor.className = "form-control error";
        valido = false;
    } else {
        cifProveedor.className = "form-control exito";
    }

    return valido;
}

function validarAñadirNombre() {
    const nombre = document.getElementById('añadirNombre_proveedor');
    const resultado = document.getElementById('resultadoProveedor');
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
    } else if (!/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*$/.test(nombre.value)){
        resultado.innerHTML += "El nombre no cumple con el formato.<br>";
        nombre.className = "form-control error";
        valido = false;
    } else {
        nombre.className = "form-control exito";
    }

    return valido;
}

function validarAñadirDireccion() {
    let direccion = document.getElementById('añadirDireccion_proveedor');
    let resultado = document.getElementById('resultadoProveedor');
    let valido = true;

    if (direccion.value.trim() === "") {
        resultado.innerHTML += "La direccion no puede estar vacía.<br>";
        direccion.className = "form-control error";
        direccion.focus();
        valido = false;
    } else if (!/^(Calle|Avenida|Plaza)\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+,\s*\d{1,4},\s*\d{5}\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+(\s*\([a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+\))?$/.test(direccion.value)) {
        resultado.innerHTML += "La dirección no cumple con los requisitos.<br>";
        direccion.className = "form-control error";
        valido = false;
    } else {
        direccion.className = "form-control exito";
    }
    return valido;
}

function validarAñadirTelefono() {
    let telefono = document.getElementById('añadirTelefono');
    let resultado = document.getElementById('resultadoProveedor');
    let valido = true;

    if (telefono.value.trim() === "") {
        resultado.innerHTML += "El telefono no puede estar vacío.<br>";
        telefono.className = "form-control error";
        telefono.focus();
        valido = false;
    } else if (!/^[6789][0-9]{8}$/.test(telefono.value)) {
        resultado.innerHTML += "La telefono no cumple con los requisitos.<br>";
        telefono.className = "form-control error";
        valido = false;
    } else {
        telefono.className = "form-control exito";
    }
    return valido;
}

function resetearFormularioAñadirProveedor() {
    const resultado = document.getElementById('resultadoProveedor');
    const inputs = document.getElementsByClassName('form-control');
    
    resultado.innerHTML = "";

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
        inputs[i].value = "";
    }
}
