<?php
session_start();
require_once 'Conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cif = trim($_POST['CIF']);
    $conexionBaseDatos = Conexion::conexionBD();

    $eliminarProveedor = $conexionBaseDatos->prepare("DELETE FROM Proveedores WHERE CIF = ?");
    $eliminarProveedor->bind_param("s", $cif);

    try {
        $eliminarProveedor->execute();
        $_SESSION['mensaje'] = "Proveedor eliminado correctamente.";
    } catch (Exception $e) {
        if (str_contains($e->getMessage(), 'a foreign key constraint fails')) {
            $_SESSION['error'] = "No se puede eliminar el proveedor porque tiene productos asignados.";
        } else {
            $_SESSION['error'] = "Ocurrió un error al eliminar el proveedor.";
        }
    }
    header('Location: ../quienesSomos.php');
    exit();
}
?>