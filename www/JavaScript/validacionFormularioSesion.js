window.addEventListener('load',iniciar,false);
// Aquí le digo al botón enviar que cuando el usaurio pulse el botón llame a la función validar
function iniciar() {
    let enviar = document.getElementById('enviar');
    enviar.addEventListener('click',validar,false);
}
// Aquí valido los campos que me vienen del formulario y si alguno no está bien prevengo el envío del formulario
function validar(eventoPorDefecto) {
    const resultado = document.getElementById('resultado');
    resultado.innerHTML = "";

    let usuarioValido = validarUsuario();
    let contrasenaValida = validarContrasena();

    let formularioValido = false;

    if (usuarioValido  && contrasenaValida) {
        formularioValido = true;
    } else {
        eventoPorDefecto.preventDefault();
        formularioValido = false;
    }

    return formularioValido;
}

// Función validar usuario
function validarUsuario() {
    let nombreUsuario = document.getElementById('usuario').value;
    let resultado = document.getElementById('resultado');
    let devolver = true;

    if (nombreUsuario === "") {
        resultado.innerHTML += "El nombre de usuario no puede estar vacío.<br>";
        document.getElementById('usuario').focus();
        document.getElementById('usuario').className = "form-control error";
        devolver = false;
    } else if (!/^[A-Za-z][a-z0-9]{0,11}$/.test(nombreUsuario)) {
        resultado.innerHTML += 
        "El nombre de usuario no cumple con los requisitos:<br>" +
        "- Deben empezar con una letra.<br>" +
        "- Pueden contener letras minúsculas y números.<br>" +
        "- Longitud entre 1 y 12 caracteres.<br>" +
        "- No se permiten símbolos ni espacios.<br><br>";
        document.getElementById('usuario').className = "form-control error";
        devolver = false;
    } else {
        document.getElementById('usuario').className = "form-control exito";
    }
    return devolver;
}
// Función validar contraseña
function validarContrasena() {
    let contrasena = document.getElementById('contrasena').value;
    let resultado = document.getElementById('resultado');
    let devolver = true;

    if (contrasena === "") {
        resultado.innerHTML += "La contraseña no puede estar vacía.<br>";
        document.getElementById('contrasena').focus();
        document.getElementById('contrasena').className = "form-control error";
        devolver = false;
    } else if (!/^[A-Z][a-z0-9]*[.]?[a-z0-9]*$/.test(contrasena)) {
        resultado.innerHTML +=
        "La contraseña no cumple con los requisitos.<br>" +
        "-La contraseña debe empezar con una letra mayúscula.<br>" +
        "-Luego puede tener letras minúsculas y números.<br>" +
        "-Puede incluir un solo punto opcional en cualquier lugar después de la primera letra.<br>" +
        "-No permite otros símbolos ni caracteres especiales.<br><br>";
        document.getElementById('contrasena').className = "form-control error";
        devolver = false;
    } else {
        document.getElementById('contrasena').className = "form-control exito";
    }
    return devolver;
}






