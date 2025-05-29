window.addEventListener('load',iniciar,false);
// Aquí llamo al botón enviar y le creo un evento para que cuando se pulse llame a función de validar
function iniciar() {
    let enviar = document.getElementById('enviar');
    enviar.addEventListener('click',validar,false);
}
// Aquí valido los campos y prevengo el envío del formulario si los campos no son correctos.
function validar(eventoPorDefecto) {
    const resultado = document.getElementById('resultado');
    resultado.innerHTML = "";

    let dniValido = validarDni();
    let usuarioValido = validarUsuario();
    let emailvalido = validarEmail();
    let direccionValida = validarDireccion();
    let contrasenaValida = validarContrasena();

    let formularioValido = false;

    if (dniValido && usuarioValido && emailvalido && direccionValida  && contrasenaValida) {
        formularioValido = true;
    } else {
        eventoPorDefecto.preventDefault();
        formularioValido = false;
    }

    return formularioValido;
}
// Función validar dni
function validarDni() {
    let dni = document.getElementById('dni').value.toUpperCase(); // Asegura que la letra sea mayúscula
    let resultado = document.getElementById('resultado');
    let devolver = true;
    resultado.innerHTML = ""; // Limpia mensajes anteriores

    if (dni === "") {
        resultado.innerHTML += "El dni no puede estar vacío.<br>";
        document.getElementById('dni').focus();
        document.getElementById('dni').className = "form-control error";
        devolver = false;
    } else if (!/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/.test(dni)) {
        resultado.innerHTML += "El dni introducido no tiene el formato correcto.<br>";
        document.getElementById('dni').className = "form-control error";
        devolver = false;
    } else {
        const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        const numero = parseInt(dni.substring(0, 8));
        const letra = dni.charAt(8);

        if (letras[numero % 23] !== letra) {
            resultado.innerHTML += "La letra del DNI no es correcta.<br>";
            document.getElementById('dni').className = "form-control error";
            devolver = false;
        } else {
            document.getElementById('dni').className = "form-control exito";
        }
    }
    return devolver;
}
// Función validar email
function validarEmail() {
    let email = document.getElementById('email').value;
    let resultado = document.getElementById('resultado');
    let devolver = true;

    if (email === "") {
        resultado.innerHTML += "El email no puede estar vacío.<br>";
        document.getElementById('email').focus();
        document.getElementById('email').className = "form-control error";
        devolver = false;
    } else if (!/^[^_]+@[^_]+(\.es|\.com)$/.test(email)) {
        resultado.innerHTML += "El email no cumple con los requisitos.<br>";
        document.getElementById('email').className = "form-control error";
        devolver = false;
    } else {
        document.getElementById('email').className = "form-control exito";
    }
    return devolver;
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
// función validar dirección
function validarDireccion() {
    let direccion = document.getElementById('direccion').value;
    let resultado = document.getElementById('resultado');
    let devolver = true;

    if (direccion === "") {
        resultado.innerHTML += "La direccion no puede estar vacía.<br>";
        document.getElementById('direccion').focus();
        document.getElementById('direccion').className = "form-control error";
        devolver = false;
    } else if (!/^(Calle|Avenida|Plaza)\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+,\s*\d{1,4},\s*\d{5}\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+(\s*\([a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+\))?$/.test(direccion)) {
        resultado.innerHTML += 
        "La dirección no cumple con los requisitos.<br>" +
        "-[Tipo de vía] [Nombre de la vía], [Número], [Código Postal] [Ciudad] (opcional: [Provincia]). <br>" +
        "-ej: Avenida de América, 45, 28028 Madrid (Madrid). <br><br>";
        document.getElementById('direccion').className = "form-control error";
        devolver = false;
    } else {
        document.getElementById('direccion').className = "form-control exito";
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

