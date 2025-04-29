<?php
session_start();
require_once 'Conexion.php';

// Solo permitir admins
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $origen = $_POST['origen'] ?? 'jerseys';

    $cod_producto = trim($_POST['cod_producto']);
    $conexionBaseDatos = Conexion::conexionBD();

    $eliminarProducto = $conexionBaseDatos->prepare("DELETE FROM Productos WHERE cod_producto = ?");
    $eliminarProducto->bind_param("s", $cod_producto);

    if ($eliminarProducto->execute()) {
        $_SESSION['mensaje'] = "Producto eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el producto.";
    }
    header('Location: ../'.$origen.'.php');
    exit();
}
?>
