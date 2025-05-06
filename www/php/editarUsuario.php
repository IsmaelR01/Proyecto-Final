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

    $dni = filter_input(INPUT_POST, 'dni');
    $nombre = validarUsuario(filter_input(INPUT_POST, 'nombre'));
    $email = validarEmail(filter_input(INPUT_POST, 'email'));
    $direccion = validarDireccion(filter_input(INPUT_POST,'direccion'));
    $conexionBaseDatos = Conexion::conexionBD();



    if (!$nombre || !$email || !$direccion) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: administrarUsuarios.php');
        exit();
    } else {
        
        $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, email = ?, direccion = ? WHERE dni = ?");
        $editarUsuario->bind_param("ssss", $nombre, $email, $direccion, $dni);
        $editarUsuario->execute();
        if ($editarUsuario->affected_rows > 0) {
            $_SESSION['mensaje'] = "Usuario editado correctamente.";
        } else {
            $_SESSION['error'] = "Error al editar el usuario.";
        }
        header('Location: administrarUsuarios.php');
        exit();
    }
}
?>
