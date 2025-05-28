function iniciarEditarProducto(codProducto) {
    const formulario = document.getElementById(`formularioEditarProducto_${codProducto}`);
    const cancelar = document.getElementById(`botonEditarCancelar_${codProducto}`);
    const cerrar = document.getElementById(`botonEditarCerrar_${codProducto}`);

    if (formulario) {
        formulario.addEventListener('submit', function (evento) {
            const valido = validarFormularioEditarProducto(evento, codProducto);
            if (!valido) {
                evento.preventDefault(); 
            }
        }, false);
    }

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
        resultado.innerHTML += "El nombre no cumple con el formato.<br>";
        nombre.className = "form-control error";
        valido = false;
    } else {
        nombre.className = "form-control exito";
    }

    return valido;
}

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

function resetearFormularioEditarProducto(codProducto) {
    const resultado = document.getElementById(`resultadoEditarProducto_${codProducto}`);
    resultado.innerHTML = "";

    const formulario = document.getElementById(`formularioEditarProducto_${codProducto}`);
    if (formulario) {
        const inputs = formulario.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('error', 'exito');
        });
    }
}
