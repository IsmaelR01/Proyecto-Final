window.addEventListener('load', iniciarEditarProveedor, false);

function iniciarEditarProveedor(cif) {
    const formulario = document.getElementById(`formularioEditarProveedor_${cif}`);
    const cancelar = document.getElementById(`botonEditarCancelar_${cif}`);
    const cerrar = document.getElementById(`botonEditarCerrar_${cif}`);

    if (formulario) {
        formulario.addEventListener('submit', function (evento) {
            const valido = validarFormularioProveedor(evento, cif);
            if (!valido) {
                evento.preventDefault(); 
            }
        }, false);
    }

    if (cancelar) {
        cancelar.addEventListener('click', function () {
            resetearFormularioEditarProveedor(cif);
        }, false);
    }

    if (cerrar) {
        cerrar.addEventListener('click', function () {
            resetearFormularioEditarProveedor(cif);
        }, false);
    }
}

function validarFormularioProveedor(evento, cif) {
    const resultado = document.getElementById(`resultadoEditarProveedor_${cif}`);
    resultado.innerHTML = ""; 

    let nombre = validarEditarNombre(cif);
    let direccion = validarEditarDireccion(cif);
    let telefono = validarEditarTelefono(cif);

    let formularioValido = true;

    if(!(nombre && direccion && telefono)) {
        evento.preventDefault();
        formularioValido = false
    }

    return formularioValido;
}

function validarEditarNombre(cif) {
    const nombre = document.getElementById(`editarNombre_proveedor_${cif}`);
    const resultado = document.getElementById(`resultadoEditarProveedor_${cif}`);
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
    } else if (!/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*$/.test(nombre.value)) {
        resultado.innerHTML += "El nombre no cumple con el formato.<br>";
        nombre.className = "form-control error";
        valido = false;
    } else {
        nombre.className = "form-control exito";
    }

    return valido;
}

function validarEditarDireccion(cif) {
    let direccion = document.getElementById(`editarDireccion_proveedor_${cif}`);
    let resultado = document.getElementById(`resultadoEditarProveedor_${cif}`);
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

function validarEditarTelefono(cif) {
    let telefono = document.getElementById(`editarTelefono_${cif}`);
    let resultado = document.getElementById(`resultadoEditarProveedor_${cif}`);
    let devolver = true;

    if (telefono.value === "") {
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

function resetearFormularioEditarProveedor(cif) {
    const resultado = document.getElementById(`resultadoEditarProveedor_${cif}`);
    const inputs = document.getElementsByClassName('form-control');
    
    resultado.innerHTML = "";

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
    }
}