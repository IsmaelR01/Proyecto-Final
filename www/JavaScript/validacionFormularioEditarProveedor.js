window.addEventListener('load', iniciarEditarProveedor, false);
// La función tendrá como parámetro el cif para controlar de qué proveedor se está realizando la edición
function iniciarEditarProveedor(cif) {
    const formulario = document.getElementById(`formularioEditarProveedor_${cif}`);
    const cancelar = document.getElementById(`botonEditarCancelar_${cif}`);
    const cerrar = document.getElementById(`botonEditarCerrar_${cif}`);
    /* La función de validar el formulario del proveedor tendrá el parámetro evento para controlar el envio de los formularios y el cif que se lo pasamos 
    como id para identificar el proveedor que se quiere editar */
    if (formulario) {
        formulario.addEventListener('submit', function (evento) {
            const valido = validarFormularioProveedor(evento, cif);
            if (!valido) {
                evento.preventDefault(); 
            }
        }, false);
    }
    // Estos botones se encargarán de borrar los mensajes de error o éxito que apapezcan además del color de los bordes de los campos
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
/* Todas las funciones que controlan los campos tendrán como parámetro el cif incluido el contenedor donde se mostrarán los mensajes para que no haya
problemas a la hora de que el modal sepa que proveedor está editando y así poder mostrar los mensajes sin problemas */
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
// Función validar nombre
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
        resultado.innerHTML += 
        "El nombre no cumple con el formato.<br>" +
        "- Cada palabra debe empezar con letra mayúscula.<br>" +
        "- Solo se permiten letras y espacios.<br>" +
        "- No se permiten números ni símbolos.<br><br>";
        nombre.className = "form-control error";
        valido = false;
    } else {
        nombre.className = "form-control exito";
    }

    return valido;
}
// Función validar dirección
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
        resultado.innerHTML += 
        "La dirección no cumple con los requisitos.<br>" +
        "-[Tipo de vía] [Nombre de la vía], [Número], [Código Postal] [Ciudad] (opcional: [Provincia]). <br>" +
        "-ej: Avenida de América, 45, 28028 Madrid (Madrid). <br><br>";
        direccion.className = "form-control error";
        devolver = false;
    } else {
        direccion.className = "form-control exito";
    }
    return devolver;
}
// Función validar teléfono
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
        resultado.innerHTML +=
        "El teléfono no cumple con los requisitos:<br>" +
        "- Debe tener 9 dígitos.<br>" +
        "- Debe comenzar con 6, 7, 8 o 9.<br>" +
        "- Solo se permiten números, sin espacios ni símbolos.<br><br>";
        telefono.className = "form-control error";
        devolver = false;
    } else {
        telefono.className = "form-control exito";
    }
    return devolver;
}
// Aquí borro del contenedor todos los mensajes y quito el color a los bordes de los campos
function resetearFormularioEditarProveedor(cif) {
    const resultado = document.getElementById(`resultadoEditarProveedor_${cif}`);
    const inputs = document.getElementsByClassName('form-control');
    
    resultado.innerHTML = "";

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
    }
}