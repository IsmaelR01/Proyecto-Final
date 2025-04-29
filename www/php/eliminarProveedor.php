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

    if ($eliminarProveedor->execute()) {
        $_SESSION['mensaje'] = "Proveedor eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el proveedpr. El proveedor tiene productos asignados";
    }
    header('Location: ../quienesSomos.php');
    exit();
}
?>