window.addEventListener('load',iniciar,false);

function iniciar() {
    let enviar = document.getElementById('enviar');
    enviar.addEventListener('click',validar,false);
}

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
        resultado.innerHTML += "El nombre de usuario no cumple con los requisitos.<br>";
        document.getElementById('usuario').className = "form-control error";
        devolver = false;
    } else {
        document.getElementById('usuario').className = "form-control exito";
    }
    return devolver;
}

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
        resultado.innerHTML += "La contraseña no cumple con los requisitos.<br>";
        document.getElementById('contrasena').className = "form-control error";
        devolver = false;
    } else {
        document.getElementById('contrasena').className = "form-control exito";
    }
    return devolver;
}






