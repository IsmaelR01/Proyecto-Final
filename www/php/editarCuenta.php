<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

if (!isset($_SESSION['identificadorUsuario'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dniUsuarioSeleccionado = $_SESSION['identificadorUsuario'];
    $nombre = validarUsuario(filter_input(INPUT_POST, 'nombre'));
    $direccion = validarDireccion(filter_input(INPUT_POST,'direccion'));
    $conexionBaseDatos = Conexion::conexionBD();

    $nuevaImagen = '';
    if (isset($_FILES['perfil']) && $_FILES['perfil']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = basename($_FILES['perfil']['name']);
        $rutaDestino = 'imagenes/fotosPerfil/' . $nombreImagen;
        $rutaServidor = '../' . $rutaDestino;

        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        $extensionArchivo = strtolower(pathinfo($nombreImagen, PATHINFO_EXTENSION));

        if (!in_array($extensionArchivo, $extensionesPermitidas)) {
            $_SESSION['error'] = "Solo se permiten imágenes JPG, JPEG o PNG.";
            header('Location: cuenta.php');
            exit();
        }

        if (!move_uploaded_file($_FILES['perfil']['tmp_name'], $rutaServidor)) {
            $_SESSION['error'] = "Error al subir la nueva imagen.";
            header('Location: cuenta.php');
            exit();
        }
        $nuevaImagen = $rutaDestino;
    }



    if (!$nombre || !$direccion) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: cuenta.php');
        exit();
    } else {
        if($nuevaImagen !== '') {
            $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, direccion = ?, perfil = ? WHERE dni = ?");
            $editarUsuario->bind_param("ssss", $nombre, $direccion, $nuevaImagen,$dniUsuarioSeleccionado);
        } else {
            $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, direccion = ? WHERE dni = ?");
            $editarUsuario->bind_param("sss", $nombre, $direccion, $dniUsuarioSeleccionado);
        }
        if ($editarUsuario->execute()) {
            if($nuevaImagen !== '') {
                $_SESSION['perfil'] = $nuevaImagen;
            }
            $_SESSION['mensaje'] = "Se han guardado correctamente los cambios";
        } else {
            $_SESSION['error'] = "Error al guardar los cambios";
        }
        header('Location: cuenta.php');
        exit();
    }
}
?>