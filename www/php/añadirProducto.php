<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

// Solo permitir admins
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Solo responder a POST
if (filter_has_var(INPUT_POST, 'origen')) {
    $origen = validarCadena(filter_input(INPUT_POST, 'origen'));

    $cod_producto = validarCodigoProducto(filter_input(INPUT_POST, 'cod_producto'));
    $nombre = validarNombre(filter_input(INPUT_POST, 'nombre'));
    $modelo = validarModelo(filter_input(INPUT_POST, 'modelo'));
    $descripcion = validarCadena(filter_input(INPUT_POST, 'descripcion'));
    $precioProducto = validarPrecio(filter_input(INPUT_POST, 'precio'));
    $cif = validarCif(filter_input(INPUT_POST, 'cif'));

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $rutaDestino = 'imagenes/productos/' . $nombreImagen;
        $rutaServidor = '../' . $rutaDestino;

        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        $extensionArchivo = strtolower(pathinfo($nombreImagen, PATHINFO_EXTENSION));

        if (!in_array($extensionArchivo, $extensionesPermitidas)) {
            $_SESSION['error'] = "Solo se permiten imágenes JPG, JPEG o PNG.";
            header('Location: ../'.$origen.'.php');
            exit();
        }

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaServidor)) {
            $_SESSION['error'] = "Error al subir la imagen.";
            header('Location: ../'.$origen.'.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Debes seleccionar una imagen válida.";
        header('Location: ../'.$origen.'.php');
        exit();
    }

    $conexionBaseDatos = Conexion::conexionBD();

    if (!$cod_producto || !$nombre || !$modelo || !$descripcion || !$precioProducto || !$rutaDestino) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: ../'.$origen.'.php');
        exit();
    } else {
        $insertarProducto = $conexionBaseDatos->prepare("INSERT INTO Productos (cod_producto, nombre, modelo, descripcion, precio, CIF, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertarProducto->bind_param("ssisdss", $cod_producto, $nombre, $modelo, $descripcion, $precioProducto, $cif, $rutaDestino);

        if ($insertarProducto->execute()) {
            if ($insertarProducto->affected_rows > 0) {
                $_SESSION['mensaje'] = "Producto añadido correctamente.";
            } else {
                $_SESSION['error'] = "No se ha añadido ningún producto. El código del producto ya existe.";
            }
        } else {
            $_SESSION['error'] = "Error al ejecutar la inserción del producto.";
        }

        // Cerrar consulta y conexión
        $insertarProducto->close();
        Conexion::cerrarConexionBD();

        header('Location: ../'.$origen.'.php');
        exit();
    }
}
?>
