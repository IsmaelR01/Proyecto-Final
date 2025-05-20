<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

// Solo permitir admins
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if (filter_has_var(INPUT_POST, 'origen') && filter_has_var(INPUT_POST, 'cod_producto')) {
    $origen = validarCadena(filter_input(INPUT_POST, 'origen'));
    $cod_producto = validarCodigoProducto(filter_input(INPUT_POST, 'cod_producto'));

    $conexionBaseDatos = Conexion::conexionBD();

    $eliminarProducto = $conexionBaseDatos->prepare("DELETE FROM Productos WHERE cod_producto = ?");
    $eliminarProducto->bind_param("s", $cod_producto);

    if ($eliminarProducto->execute()) {
        if ($eliminarProducto->affected_rows > 0) {
            $_SESSION['mensaje'] = "Producto eliminado correctamente.";
        } else {
            $_SESSION['error'] = "No se ha eliminado ningún producto. Es posible que el código no exista.";
        }
    } else {
        $_SESSION['error'] = "Error al ejecutar la eliminación del producto.";
    }

    // Cerrar consulta y conexión
    $eliminarProducto->close();
    Conexion::cerrarConexionBD();

    header('Location: ../' . $origen . '.php');
    exit();
}
?>
