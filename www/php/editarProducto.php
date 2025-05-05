<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

// Solo permitir admins
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $origen = $_POST['origen'] ?? 'jerseys';

    $cod_producto = validarCodigoProducto(filter_input(INPUT_POST, 'cod_producto'));
    $nombre = validarNombre(filter_input(INPUT_POST, 'nombre'));
    $modelo = validarModelo(filter_input(INPUT_POST, 'modelo'));
    $descripcion = trim($_POST['descripcion']);
    $precioProducto = validarPrecio(filter_input(INPUT_POST,'precio'));
    $conexionBaseDatos = Conexion::conexionBD();

    // Gestionar nueva imagen si se envía
    $nuevaImagen = '';
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
            $_SESSION['error'] = "Error al subir la nueva imagen.";
            header('Location: ../'.$origen.'.php');
            exit();
        }
        $nuevaImagen = $rutaDestino;
    }

    if (!$cod_producto || !$nombre || !$modelo || !$descripcion || !$precioProducto) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: ../'.$origen.'.php');
        exit();
    } else {
        if ($nuevaImagen !== '') {
            $editarProducto = $conexionBaseDatos->prepare("UPDATE Productos SET nombre = ?, modelo = ?, descripcion = ?, precio = ?, imagen = ? WHERE cod_producto = ?");
            $editarProducto->bind_param("sisdss", $nombre, $modelo, $descripcion, $precioProducto, $nuevaImagen, $cod_producto);
        } else {
            $editarProducto = $conexionBaseDatos->prepare("UPDATE Productos SET nombre = ?, modelo = ?, descripcion = ?, precio = ? WHERE cod_producto = ?");
            $editarProducto->bind_param("sisds", $nombre, $modelo, $descripcion, $precioProducto, $cod_producto);
        }

        if ($editarProducto->execute()) {
            $_SESSION['mensaje'] = "Producto editado correctamente.";
        } else {
            $_SESSION['error'] = "Error al editar el producto.";
        }
        header('Location: ../'.$origen.'.php');
        exit();
    }
}
?>
