<?php
session_start();
require_once 'Conexion.php';

// Solo permitir admins
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dniUsuarioSeleccionado = filter_input(INPUT_POST, 'seleccionUsuario');
    $conexionBaseDatos = Conexion::conexionBD();

    $eliminarUsuario = $conexionBaseDatos->prepare("DELETE FROM Usuarios WHERE dni = ?");
    $eliminarUsuario->bind_param("s", $dniUsuarioSeleccionado);

    if ($eliminarUsuario->execute()) {
        $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el usuario.";
    }
    $_SESSION['accion'] = 'eliminar';
    $_SESSION['seleccionUsuario'] = $dniUsuarioSeleccionado;
    header('Location: administrarUsuarios.php');
    exit();
}
?>