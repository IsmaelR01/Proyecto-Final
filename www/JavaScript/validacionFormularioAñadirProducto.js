window.addEventListener('load', iniciarAñadirProducto, false);
// Aquí llamo a los diferentes botones para asociarle los diferentes eventos que quiero que salten.
function iniciarAñadirProducto() {
    const enviar = document.getElementById('botonAñadirEnviar'); 
    enviar.addEventListener('click', validarFormularioAñadirProducto, false); 
    const cancelar = document.getElementById('botonAñadirCancelar');
    cancelar.addEventListener('click', resetearFormularioAñadirProducto, false); 
    const cerrar = document.getElementById('botonAñadirCerrar');
    cerrar.addEventListener('click', resetearFormularioAñadirProducto, false); 
}
// Aquí valido el formulario con el parámetro evento para evitar enviar un formulario con campos erróneos
function validarFormularioAñadirProducto(evento) {
    const resultado = document.getElementById('resultadoProducto');
    resultado.innerHTML = ""; 

    let codigoProducto = validarAñadirCodigo();
    let nombre = validarAñadirNombre();
    let modelo = validarAñadirModelo();
    let precio= validarAñadirPrecio();
    let proveedor = validarCif();
    let descripcion = validarAñadirDescripcion();
    let imagen = validarAñadirImagen(); 

    let formularioValido = false;

    if (codigoProducto && nombre && modelo && precio && proveedor && descripcion && imagen) {
        formularioValido = true;
    } else {
        evento.preventDefault();
        formularioValido = false;
    }

    return formularioValido;
}
// Función validar código producto
function validarAñadirCodigo() {
    const codigoProducto = document.getElementById('añadirCod_producto');
    const resultado = document.getElementById('resultadoProducto');
    let valido = true;

    resultado.innerHTML = ""; 

    if (codigoProducto.value.trim() === "") {
        resultado.innerHTML += "El código no puede estar vacío.<br>";
        codigoProducto.className = "form-control error";
        codigoProducto.focus();
        valido = false;
    } else if (!/^[CJ][0-9]{4}$/.test(codigoProducto.value)) {
        resultado.innerHTML +=
        "El código debe tener exactamente 5 caracteres.<br>" +
        "Debe comenzar con la letra 'C' o 'J' mayúscula.<br>" +
        "Los siguientes 4 caracteres deben ser números del 0 al 9.<br><br>";
        codigoProducto.className = "form-control error";
        valido = false;
    } else {
        codigoProducto.className = "form-control exito";
    }

    return valido;
}
// Función validar nombre
function validarAñadirNombre() {
    const nombre = document.getElementById('añadirNombre');
    const resultado = document.getElementById('resultadoProducto');
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
// Funcióm validar dirección
function validarAñadirModelo() {
    const modelo = document.getElementById('añadirModelo');
    const resultado = document.getElementById('resultadoProducto');
    let valido = true;


    if (modelo.value.trim() === "" || isNaN(modelo.value) || Number(modelo.value) < 2010 || Number(modelo.value > 2035)) {
        resultado.innerHTML += "El modelo debe estar comprendido entre 2010 y 2035.<br>";
        modelo.className = "form-control error";
        modelo.focus();
        valido = false;
    } else if(!/^\d{4}$/.test(modelo.value)) {
        resultado.innerHTML += "El modelo no cumple con el formato numérico establecido.<br>";
        modelo.className = "form-control error";
        valido = false;
    }
    else {
        modelo.className = "form-control exito";
    }

    return valido;
}
// Función validar dirección
function validarAñadirPrecio() {
    const precio = document.getElementById('añadirPrecio');
    const resultado = document.getElementById('resultadoProducto');
    let valido = true;


    if (!/^\d+(\.\d{1,2})?$/.test(precio.value)) {
        resultado.innerHTML += "El precio debe ser un número válido con hasta 2 decimales.<br>";
        precio.className = "form-control error";
        precio.focus();
        valido = false;
    } else if (precio.value.trim() === "" || isNaN(precio.value) || Number(precio.value) < 9.99 || Number(precio.value > 999.99)) {
        resultado.innerHTML += "El precio debe ser un número decimal, estar comprendido entre 9.99 y 999.99 y no puede estar vacío<br>";
        precio.className = "form-control error";
        valido = false;
    } else {
        precio.className = "form-control exito";
    }

    return valido;
}
// Función validar descripción
function validarAñadirDescripcion() {
    const descripcion = document.getElementById('añadirDescripcion');
    const resultado = document.getElementById('resultadoProducto');
    let valido = true;


    if (descripcion.value.trim() === "") {
        resultado.innerHTML += "La descripción no puede estar vacía.<br>";
        descripcion.className = "form-control error";
        descripcion.focus();
        valido = false;
    } else {
        descripcion.className = "form-control exito";
    }

    return valido;
}
// Función validar CIF
function validarCif() {
    const cif = document.getElementById('cif');
    const resultado = document.getElementById('resultadoProducto');
    let valido = true;


    if (cif.value === "") {
        resultado.innerHTML += "Debe seleccionar un proveedor.<br>";
        cif.className = "form-control error";
        cif.focus();
        valido = false;
    } else {
        cif.className = "form-control exito";
    }

    return valido;
}
// Aquí borro del contenedor todos los mensajes y quito el color a los bordes de los campos
function validarAñadirImagen() {
    const imagen = document.getElementById('añadirImagen');
    const resultado = document.getElementById('resultadoProducto');
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
        resultado.innerHTML += "Debe subir una imagen.<br>";
        imagen.className = "form-control error";
        imagen.focus();
        valido = false;
    }

    return valido;
}

function resetearFormularioAñadirProducto() {
    const resultado = document.getElementById('resultadoProducto');
    const inputs = document.getElementsByClassName('form-control');
    
    resultado.innerHTML = "";

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
        inputs[i].value = "";
    }
}
