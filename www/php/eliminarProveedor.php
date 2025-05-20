<?php
session_start();
require_once 'Conexion.php';
include_once 'funciones_validar.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if (filter_has_var(INPUT_POST, 'CIF')) {

    $cif = validarCif(filter_input(INPUT_POST, 'CIF'));
    $conexionBaseDatos = Conexion::conexionBD();

    $eliminarProveedor = $conexionBaseDatos->prepare("DELETE FROM Proveedores WHERE CIF = ?");
    $eliminarProveedor->bind_param("s", $cif);

    try {
        $eliminarProveedor->execute();

        if ($eliminarProveedor->affected_rows > 0) {
            $_SESSION['mensaje'] = "Proveedor eliminado correctamente.";
        } else {
            $_SESSION['error'] = "No se ha eliminado ningún proveedor. Es posible que el CIF no exista o tenga productos asociados.";
        }

    } catch (Exception $e) {
        if (str_contains($e->getMessage(), 'a foreign key constraint fails')) {
            $_SESSION['error'] = "No se puede eliminar el proveedor porque tiene productos asignados.";
        } else {
            $_SESSION['error'] = "Error al eliminar el proveedor.";
        }
    }

    // Cerrar consulta y conexión
    $eliminarProveedor->close();
    Conexion::cerrarConexionBD();

    header('Location: ../quienesSomos.php');
    exit();
}
?>
