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

    $dniUsuarioSeleccionado = filter_input(INPUT_POST, 'seleccionUsuario');
    $nombre = validarUsuario(filter_input(INPUT_POST, 'nombre'));
    $email = validarEmail(filter_input(INPUT_POST, 'email'));
    $direccion = validarDireccion(filter_input(INPUT_POST, 'direccion'));

    $conexionBaseDatos = Conexion::conexionBD();

    if (!$nombre || !$email || !$direccion) {
        $_SESSION['mensaje_error'] = "Alguno de los campos introducidos no son correctos.";
    } else {
        $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, email = ?, direccion = ? WHERE dni = ?");
        $editarUsuario->bind_param("ssss", $nombre, $email, $direccion, $dniUsuarioSeleccionado);

        if ($editarUsuario->execute()) {
            if ($editarUsuario->affected_rows > 0) {
                $_SESSION['mensaje'] = "Usuario editado correctamente.";
            } else {
                $_SESSION['mensaje_error'] = "No se realizaron cambios porque los datos de los campos son iguales.";
            }
        } else {
            $_SESSION['mensaje_error'] = "Error al editar el usuario.";
        }

        $editarUsuario->close();
    }

    Conexion::cerrarConexionBD();

    $_SESSION['accion'] = 'editar';
    $_SESSION['seleccionUsuario'] = $dniUsuarioSeleccionado;

    header('Location: administrarUsuarios.php');
    exit();
}
?>
