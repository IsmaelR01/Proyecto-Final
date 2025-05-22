<?php
session_start();
require_once 'Conexion.php';

// Solo permitir admins
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if (filter_has_var(INPUT_POST, 'botonEliminarEnviar')) {

    $dniUsuarioSeleccionado = filter_input(INPUT_POST, 'seleccionUsuario');
    $conexionBaseDatos = Conexion::conexionBD();

    $eliminarUsuario = $conexionBaseDatos->prepare("DELETE FROM Usuarios WHERE dni = ?");
    $eliminarUsuario->bind_param("s", $dniUsuarioSeleccionado);

    if ($eliminarUsuario->execute()) {
        if ($eliminarUsuario->affected_rows > 0) {
            $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
        } else {
            $_SESSION['error'] = "No se ha eliminado ningún usuario. El DNI no existe.";
        }
    } else {
        $_SESSION['error'] = "Error al ejecutar la eliminación del usuario.";
    }

    // Cerrar consulta y conexión
    $eliminarUsuario->close();
    Conexion::cerrarConexionBD();

    $_SESSION['accion'] = 'eliminar';
    $_SESSION['seleccionUsuario'] = $dniUsuarioSeleccionado;
    header('Location: administrarUsuarios.php');
    exit();
}
?>
