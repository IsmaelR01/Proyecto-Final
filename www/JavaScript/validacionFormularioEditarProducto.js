// La función tendrá como parámetro el cif para controlar de qué producto se está realizando la edición
function iniciarEditarProducto(codProducto) {
    const formulario = document.getElementById(`formularioEditarProducto_${codProducto}`);
    const cancelar = document.getElementById(`botonEditarCancelar_${codProducto}`);
    const cerrar = document.getElementById(`botonEditarCerrar_${codProducto}`);
    /* La función de validar el formulario del producto tendrá el parámetro evento para controlar el envio de los formularios y el cod producto que se lo pasamos 
    como id para identificar el producto que se quiere editar */
    if (formulario) {
        formulario.addEventListener('submit', function (evento) {
            const valido = validarFormularioEditarProducto(evento, codProducto);
            if (!valido) {
                evento.preventDefault(); 
            }
        }, false);
    }
    // Estos botones se encargarán de borrar los mensajes de error o éxito que apapezcan además del color de los bordes de los campos
    if (cancelar) {
        cancelar.addEventListener('click', function () {
            resetearFormularioEditarProducto(codProducto);
        }, false);
    }

    if (cerrar) {
        cerrar.addEventListener('click', function () {
            resetearFormularioEditarProducto(codProducto);
        }, false);
    }
}
/* Todas las funciones que controlan los campos tendrán como parámetro el cod producto incluido el contenedor donde se mostrarán los mensajes para que no haya
problemas a la hora de que el modal sepa que producto está editando y así poder mostrar los mensajes sin problemas */
function validarFormularioEditarProducto(evento, codProducto) {
    const resultado = document.getElementById(`resultadoEditarProducto_${codProducto}`);
    resultado.innerHTML = "";

    let nombre = validarEditarNombre(codProducto);
    let modelo = validarEditarModelo(codProducto);
    let precio = validarEditarPrecio(codProducto);
    let descripcion = validarEditarDescripcion(codProducto);
    let imagen = validarEditarImagen(codProducto);

    let valido = true;

    if (!(nombre && modelo && precio && descripcion && imagen)) {
        evento.preventDefault();
        valido = false;
    }

    return valido;
}
// Función validar nombre
function validarEditarNombre(codProducto) {
    const nombre = document.getElementById(`editarNombre_${codProducto}`);
    const resultado = document.getElementById(`resultadoEditarProducto_${codProducto}`);
    let valido = true;

    if (nombre.value.trim() === "") {
        resultado.innerHTML += "El nombre no puede estar vacío.<br>";
        nombre.className = "form-control error";
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
// Función validar modelo
function validarEditarModelo(codProducto) {
    const modelo = document.getElementById(`editarModelo_${codProducto}`);
    const resultado = document.getElementById(`resultadoEditarProducto_${codProducto}`);
    let valido = true;

    if (modelo.value.trim() === "" || isNaN(modelo.value) || Number(modelo.value) < 2010 || Number(modelo.value) > 2035) {
        resultado.innerHTML += "El modelo debe estar comprendido entre 2010 y 2035.<br>";
        modelo.className = "form-control error";
        valido = false;
    } else if (!/^\d{4}$/.test(modelo.value)) {
        resultado.innerHTML += "El modelo no cumple con el formato numérico establecido.<br>";
        modelo.className = "form-control error";
        valido = false;
    } else {
        modelo.className = "form-control exito";
    }

    return valido;
}
// Función validar precio
function validarEditarPrecio(codProducto) {
    const precio = document.getElementById(`editarPrecio_${codProducto}`);
    const resultado = document.getElementById(`resultadoEditarProducto_${codProducto}`);
    let valido = true;

    if (!/^\d+(\.\d{1,2})?$/.test(precio.value)) {
        resultado.innerHTML += "El precio debe ser un número válido con hasta 2 decimales.<br>";
        precio.className = "form-control error";
        valido = false;
    } else {
        precio.className = "form-control exito";
    }

    return valido;
}
// Función validar descripción
function validarEditarDescripcion(codProducto) {
    const descripcion = document.getElementById(`editarDescripcion_${codProducto}`);
    const resultado = document.getElementById(`resultadoEditarProducto_${codProducto}`);
    let valido = true;

    if (descripcion.value.trim() === "") {
        resultado.innerHTML += "La descripción no puede estar vacía.<br>";
        descripcion.className = "form-control error";
        valido = false;
    } else {
        descripcion.className = "form-control exito";
    }

    return valido;
}
// Función validar imagen
function validarEditarImagen(codProducto) {
    const imagen = document.getElementById(`editarImagen_${codProducto}`);
    const resultado = document.getElementById(`resultadoEditarProducto_${codProducto}`);
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
    } else {
        imagen.className = "form-control exito";
    }

    return valido;
}
// Aquí borro del contenedor todos los mensajes y quito el color a los bordes de los campos
function resetearFormularioEditarProducto(codProducto) {
    const resultado = document.getElementById(`resultadoEditarProducto_${codProducto}`);
    const inputs = document.getElementsByClassName('form-control');

    resultado.innerHTML = "";
    
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
    }
}
