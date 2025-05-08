window.addEventListener('load', iniciar, false);

function iniciar() {
    const enviar = document.getElementById('botonAñadirEnviar'); // ID del botón de submit
    enviar.addEventListener('click', validarFormularioProducto, false); // Cambié el evento para usar click
    const cancelar = document.getElementById('botonAñadirCancelar'); // Botón "Cancelar"
    cancelar.addEventListener('click', resetearFormulario, false); // Resetear formulario al hacer clic
}

function validarFormularioProducto(evento) {
    const resultado = document.getElementById('resultadoProducto');
    resultado.innerHTML = ""; // Limpiar resultados de validación al principio

    let codOk = validarCodigo();
    let nombreOk = validarNombre();
    let modeloOk = validarModelo();
    let precioOk = validarPrecio();
    let proveedorOk = validarCif();
    let descripcionOk = validarDescripcion();
    let imagenOk = validarImagen(); // Solo si el input es obligatorio

    let formularioOk = codOk && nombreOk && modeloOk && precioOk && proveedorOk && descripcionOk && imagenOk;

    if (!formularioOk) {
        evento.preventDefault(); // Prevenir el envío del formulario si alguna validación falla
    }

    return formularioOk;
}

function validarCodigo() {
    const cod = document.getElementById('cod_producto');
    const resultado = document.getElementById('resultadoProducto');
    let valido = true;

    // Limpiar mensaje de error antes de validar
    resultado.innerHTML = ""; 

    if (cod.value.trim() === "") {
        resultado.innerHTML += "El código no puede estar vacío.<br>";
        cod.className = "form-control error";
        cod.focus();
        valido = false;
    } else if (!/^[A-Za-z0-9]{1,5}$/.test(cod.value)) {
        resultado.innerHTML += "El código debe tener máximo 5 caracteres alfanuméricos.<br>";
        cod.className = "form-control error";
        valido = false;
    } else {
        cod.className = "form-control exito";
    }

    return valido;
}

function validarNombre() {
    const nombre = document.getElementById('nombre');
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
    } else {
        nombre.className = "form-control exito";
    }

    return valido;
}

function validarModelo() {
    const modelo = document.getElementById('modelo');
    const resultado = document.getElementById('resultadoProducto');
    let valido = true;


    if (modelo.value.trim() === "" || isNaN(modelo.value) || Number(modelo.value) < 0) {
        resultado.innerHTML += "El modelo debe ser un número positivo.<br>";
        modelo.className = "form-control error";
        modelo.focus();
        valido = false;
    } else {
        modelo.className = "form-control exito";
    }

    return valido;
}

function validarPrecio() {
    const precio = document.getElementById('precio');
    const resultado = document.getElementById('resultadoProducto');
    let valido = true;


    if (!/^\d+(\.\d{1,2})?$/.test(precio.value)) {
        resultado.innerHTML += "El precio debe ser un número válido con hasta 2 decimales.<br>";
        precio.className = "form-control error";
        precio.focus();
        valido = false;
    } else {
        precio.className = "form-control exito";
    }

    return valido;
}

function validarDescripcion() {
    const descripcion = document.getElementById('descripcion');
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

function validarImagen() {
    const imagen = document.getElementById('imagen');
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

function resetearFormulario() {
    const resultado = document.getElementById('resultadoProducto');
    const inputs = document.getElementsByClassName('form-control');
    
    // Limpiar los mensajes de error
    resultado.innerHTML = "";

    // Eliminar clases de éxito y error
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].classList.remove('error', 'exito');
    }
}
