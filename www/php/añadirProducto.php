<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

// Si no existe la sesión del usuario y su rol no es admin lo redirijo a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Comrpuebo si existe la variable del campo oculto que es origen
if (filter_has_var(INPUT_POST, 'origen')) {
    /* valido todos los campos incluido el campo oculto con funciones de validacion para evitar inyecciones XSS o html,
    el campo oculto origen lo utilizo para saber si el formulario viene del archivo jerseys.php o chaquetas.php */
    $origen = validarCadena(filter_input(INPUT_POST, 'origen'));

    $cod_producto = validarCodigoProducto(filter_input(INPUT_POST, 'cod_producto'));
    $nombre = validarNombre(filter_input(INPUT_POST, 'nombre'));
    $modelo = validarModelo(filter_input(INPUT_POST, 'modelo'));
    $descripcion = validarCadena(filter_input(INPUT_POST, 'descripcion'));
    $precioProducto = validarPrecio(filter_input(INPUT_POST, 'precio'));
    $cif = validarCif(filter_input(INPUT_POST, 'cif'));
    // Creo una variable con los orígenes permitidos
    $origenesPermitidos = ['jerseys', 'chaquetas']; 
    // Si el origen no es válido o no está en el array de permitidos lo redirijo a la página principal
    if (!in_array($origen, $origenesPermitidos) || !$origen) {
        $_SESSION['error'] = 'Origen desconocido';
        header("Location: ../index.php");
        exit();
    }

    //Aqui compruebo si se ha enviado el archivo imagen y no ha habido ningún error en la subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Se obtiene el nombre del archivo sin los directorios
        $nombreImagen = basename($_FILES['imagen']['name']);
        // Aquí especifico la ruta donde se va a guardar el archivo
        $rutaDestino = 'imagenes/productos/' . $nombreImagen;
        // Aquí especifico la ruta donde actualmente se encuentra el sistema de archivos
        $rutaServidor = '../' . $rutaDestino;
        // Aqui me creo un array con las extensiones que voy a permitir
        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        // Aquí convierto la extensión del archivo a minúsculas si el usuario ha introducido la extensión con mayúsculas
        $extensionArchivo = strtolower(pathinfo($nombreImagen, PATHINFO_EXTENSION));
        /* Si la extensión del archivo no coincide con las extensiones permitidas del array redirijo al usuario al origen de donde proviene
        el envío del formulario y le muestro un mensaje de error */
        if (!in_array($extensionArchivo, $extensionesPermitidas)) {
            $_SESSION['error'] = "Solo se permiten imágenes JPG, JPEG o PNG.";
            header('Location: ../' . $origen . '.php');
            exit();
        }
        /* Aquí intenta mover el archivo desde la ruta donde el usuario ha seleccionado el archivo hasta la ruta donde se guardarán las
        imágenes en el sistema de archivos, si esto falla lo redirijo a donde proviene el envío del formulario y le muestro un mensaje de error */
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaServidor)) {
            $_SESSION['error'] = "Error al subir la imagen.";
            header('Location: ../' . $origen . '.php');
            exit();
        }
    } else {
        // Si algo falla en la selección de la imagen lo redirijo desde donde proviene el envío del formulario y le muestro un mensaje de error
        $_SESSION['error'] = "Debes seleccionar una imagen válida.";
        header('Location: ../' . $origen . '.php');
        exit();
    }

    // Declaro la variable para la conexión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();

    // Si alguno de los campos falla lo redirijo hasta donde proviene el envío del formulario y le muestro un mensaje de error
    if (!$cod_producto || !$nombre || !$modelo || !$descripcion || !$precioProducto || !$rutaDestino) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: ../' . $origen . '.php');
        exit();
    } else {
        // Primero compruebo si ya existe un producto con ese código
        $consultaCodigo = $conexionBaseDatos->prepare("SELECT cod_producto FROM Productos WHERE cod_producto = ?");
        $consultaCodigo->bind_param("s", $cod_producto);
        $consultaCodigo->execute();
        $resultado = $consultaCodigo->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            // Si ya existe un producto con ese código muestro mensaje de error
            $_SESSION['error'] = "Ya existe un producto con ese código.";
            $consultaCodigo->close();
            Conexion::cerrarConexionBD();
            header('Location: ../' . $origen . '.php');
            exit();
        }
        $consultaCodigo->close();

        // Si no hay ningún campo inválido procedo con la inserción en la base de datos
        $insertarProducto = $conexionBaseDatos->prepare("INSERT INTO Productos (cod_producto, nombre, modelo, descripcion, precio, CIF, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertarProducto->bind_param("ssisdss", $cod_producto, $nombre, $modelo, $descripcion, $precioProducto, $cif, $rutaDestino);

        if ($insertarProducto->execute()) {
            if ($insertarProducto->affected_rows > 0) {
                // Si se ha ejecutado bien la inserción y las filas afectadas es mayor que 0 le muestro un mensaje de exito 
                $_SESSION['mensaje'] = "Producto añadido correctamente.";
            } else {
                // Si se ha ejecutado la inserción pero no hay filas afectadas quiere decir que no se ha añadido ningún producto
                $_SESSION['error'] = "No se ha añadido ningún producto.";
            }
        } else {
            // Si la ejecución de la inserción falla, muestro un mensaje de error genérico
            $_SESSION['error'] = "Error al ejecutar la inserción del producto.";
        }

        // Cierro la consulta y la conexión de la base de datos
        $insertarProducto->close();
        Conexion::cerrarConexionBD();

        // y redirijo al origen de donde proviene el envío del formulario
        header('Location: ../' . $origen . '.php');
        exit();
    }
}
