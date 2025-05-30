window.addEventListener('load', iniciarEditarUsuario, false);
// Aquí llamo al botón enviar para que dispare el evento de validar el formulario
function iniciarEditarUsuario() {
    const enviar = document.getElementById('botonEditarEnviar');
    if(enviar) enviar.addEventListener('click', validarFormularioUsuario, false);

}
// Le paso como parámetro para controlar el envío del formulario
function validarFormularioUsuario(evento) {
    const resultado = document.getElementById('resultadoEditarUsuario');
    resultado.innerHTML = "";

    let nombre = validarEditarNombreUsuario();
    let email = validarEditarEmailUsuario();
    let direccion = validarEditarDireccionUsuario();

    let valido = true;

    if (!(nombre && email && direccion)) {
        evento.preventDefault();
        valido = false;
    }

    return valido;
}
// Función validar nombre usuario
function validarEditarNombreUsuario() {
    let nombreUsuario = document.getElementById('editarNombreUsuario');
    let resultado = document.getElementById('resultadoEditarUsuario');
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
// Función validar email
function validarEditarEmailUsuario() {
    let email = document.getElementById('editarEmailUsuario');
    let resultado = document.getElementById('resultadoEditarUsuario');
    let devolver = true;

    if (email.value === "") {
        resultado.innerHTML += "El email no puede estar vacío.<br>";
        email.focus();
        email.className = "form-control error";
        devolver = false;
    } else if (!/^[^_]+@[^_]+(\.es|\.com)$/.test(email.value)) {
        resultado.innerHTML += "El email no cumple con los requisitos.<br>";
        email.className = "form-control error";
        devolver = false;
    } else {
        email.className = "form-control exito";
    }
    return devolver;
}
// función validar dirección
function validarEditarDireccionUsuario() {
    let direccion = document.getElementById('editarDireccionUsuario');
    let resultado = document.getElementById('resultadoEditarUsuario');
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
// Aquí borro del contenedor todos los mensajes y quito el color a los bordes de los campos
function resetearFormularioEditarUsuario() {
    const resultado = document.getElementById('resultadoEditarUsuario');
    resultado.innerHTML = "";

    const inputs = document.getElementsByClassName('form-control');

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
    }
}
