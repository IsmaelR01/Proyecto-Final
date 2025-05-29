window.addEventListener('load', iniciarEditarCuenta, false);
// aquí llamo a la funci´´on encargada de coger el id del formulario y llamar para validar los campos de los formularios
function iniciarEditarCuenta() {
    const formulario = document.getElementById('formularioEditarCuenta');
    if (formulario) {
        formulario.addEventListener('submit', validarFormularioEditarCuenta, false);
    }
}
// aquí le paso el evento como parámetro para prevenir el envio del formulario si algo está mal
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

// Función validar nombre usuario
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
        resultado.innerHTML += 
        "El nombre de usuario no cumple con los requisitos:<br>" +
        "- Deben empezar con una letra.<br>" +
        "- Pueden contener letras minúsculas y números.<br>" +
        "- Longitud entre 1 y 12 caracteres.<br>" +
        "- No se permiten símbolos ni espacios.<br><br>";
        nombreUsuario.className = "form-control error";
        devolver = false;
    } else {
        nombreUsuario.className = "form-control exito";
    }
    return devolver;
}

// Función validar dirección
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
        resultado.innerHTML += 
        "La dirección no cumple con los requisitos.<br>" +
        "[Tipo de vía] [Nombre de la vía], [Número], [Código Postal] [Ciudad] (opcional: [Provincia]). <br>" +
        "ej: Avenida de América, 45, 28028 Madrid (Madrid). <br><br>";
        direccion.className = "form-control error";
        devolver = false;
    } else {
        direccion.className = "form-control exito";
    }
    return devolver;
}
// Función validar imagen
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
// Función validar contrasena antigua
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
        resultado.innerHTML += 
        "La contraseña actual no cumple con los requisitos.<br>" +
        "-La contraseña debe empezar con una letra mayúscula.<br>" +
        "-Luego puede tener letras minúsculas y números.<br>" +
        "-Puede incluir un solo punto opcional en cualquier lugar después de la primera letra.<br>" +
        "-No permite otros símbolos ni caracteres especiales.<br><br>";
        contrasenaAntigua.className = "form-control error";
        devolver = false;
    } else {
        contrasenaAntigua.className = "form-control exito";
    }
    return devolver;
}
// Función validar contrasena nueva
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
        resultado.innerHTML += 
        "La contraseña nueva no cumple con los requisitos.<br>" +
        "-La contraseña debe empezar con una letra mayúscula.<br>" +
        "-Luego puede tener letras minúsculas y números.<br>" +
        "-Puede incluir un solo punto opcional en cualquier lugar después de la primera letra.<br>" +
        "-No permite otros símbolos ni caracteres especiales.<br><br>";
        contrasenaNueva.className = "form-control error";
        devolver = false;
    } else {
        contrasenaNueva.className = "form-control exito";
    }
    return devolver;
}
// Aquí borro del contenedor todos los mensajes y quito el color a los bordes de los campos
function resetearFormularioEditarCuenta() {
    const resultado = document.getElementById('resultadoEditarCuenta');
    resultado.innerHTML = "";

    const inputs = document.getElementsByClassName('form-control');

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
    }
}

