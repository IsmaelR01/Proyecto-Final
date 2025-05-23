<?php
date_default_timezone_set('Europe/Madrid');
session_start();
require_once 'Conexion.php';
include_once 'funciones_validar.php';

$conexionBaseDatos = Conexion::conexionBD();

// Verificar si el usuario está logueado como cliente
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'cliente') {
    $_SESSION['error'] = "Debes iniciar sesión como cliente para realizar una compra.";
    header("Location: login.php");
    exit();
}

if (filter_has_var(INPUT_POST, 'cod_producto') && filter_has_var(INPUT_POST, 'cantidad')) {
    $origen = validarCadena(filter_input(INPUT_POST, 'origen'));
    $codProducto = validarCodigoProducto(filter_input(INPUT_POST, 'cod_producto'));
    $cantidad = validarCantidad(filter_input(INPUT_POST, 'cantidad'));
    $dniCliente = $_SESSION['identificadorUsuario']; 

    // Validar cantidad
    if (!$cantidad) {
        $_SESSION['error'] = "La cantidad debe ser mínimo 1 máximo 5 unidades por producto.";
        header('Location: ../' . $origen .'.php');
        exit();
    }

    // Consultar precio actual del producto
    $consultaPrecio = $conexionBaseDatos->prepare("SELECT precio FROM Productos WHERE cod_producto = ?");
    $consultaPrecio->bind_param("s", $codProducto);
    $consultaPrecio->execute();
    $resultado = $consultaPrecio->get_result();

    if ($resultado->num_rows === 1) {
        $producto = $resultado->fetch_assoc();
        $precioUnitario = $producto['precio'];
        $subtotal = $precioUnitario * $cantidad;

        $fechaHoy = date("Y-m-d");
        $verificarCompra = $conexionBaseDatos->prepare("SELECT * FROM Compran WHERE dni = ? AND cod_producto = ? AND DATE(fecha_compra) = ?");
        $verificarCompra->bind_param("sss", $dniCliente, $codProducto, $fechaHoy);
        $verificarCompra->execute();
        $resultadoCompra = $verificarCompra->get_result();

        if ($resultadoCompra->num_rows > 0) {
            $_SESSION['error'] = "Este producto ya ha sido comprado recientemente. Vuelva otro día.";
        } else {
            $insertarCompra = $conexionBaseDatos->prepare("INSERT INTO Compran (dni, cod_producto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
            $insertarCompra->bind_param("ssid", $dniCliente, $codProducto, $cantidad, $subtotal);

            if ($insertarCompra->execute()) {
                if ($insertarCompra->affected_rows > 0) {
                    $_SESSION['mensaje'] = "¡Compra realizada con éxito!";
                } else {
                    $_SESSION['error'] = "No se pudo completar la compra. Intentalo de nuevo.";
                }
            } else {
                $_SESSION['error'] = "Error al registrar la compra.";
            }

            $insertarCompra->close();
        }

        $verificarCompra->close();
    } else {
        $_SESSION['error'] = "Producto no encontrado.";
    }

    $consultaPrecio->close();
    Conexion::cerrarConexionBD();

    header('Location: ../' . $origen .'.php');
    exit();
} else {
    $_SESSION['error'] = "Faltan datos para procesar el pedido.";
    header('Location: ../index.php');
    exit();
}
