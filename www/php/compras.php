<?php
date_default_timezone_set('Europe/Madrid');
session_start();
require_once 'Conexion.php';
include_once 'funciones_validar.php';

$conexionBaseDatos = Conexion::conexionBD();

// Verifico si existe la sesión del usuario y si el rol del usuario es cliente, si no se cunple una de las dos lo reditijo a login.php
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'cliente') {
    $_SESSION['error'] = "Debes iniciar sesión como cliente para realizar una compra.";
    header("Location: login.php");
    exit();
}
// Compruebo si existen las variables del formulario que en este caso son los campos ocultos que se mandan desde el formulario
if (filter_has_var(INPUT_POST, 'cod_producto') && filter_has_var(INPUT_POST, 'cantidad')) {
    // Valido los campos
    $origen = validarCadena(filter_input(INPUT_POST, 'origen'));
    $codProducto = validarCodigoProducto(filter_input(INPUT_POST, 'cod_producto'));
    $cantidad = validarCantidad(filter_input(INPUT_POST, 'cantidad'));
    $dniCliente = $_SESSION['identificadorUsuario']; 

    // Creo una variable con los orígenes permitidos
    $origenesPermitidos = ['jerseys', 'chaquetas']; 
    // Si el origen no es válido o no está en el array de permitidos lo redirijo a la página principal
    if (!in_array($origen, $origenesPermitidos) || !$origen) {
        $_SESSION['error'] = 'Origen desconocido';
        header("Location: ../index.php");
        exit();
    }

    // Valido que la cantidad sea válida y si no le muestro un mensaje de error de donde proviene el origen del formulario
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
        // Si el numero de filas es 1 quiere decir que el producto existe por lo que procedo a calcular el precio total de la compra
        $producto = $resultado->fetch_assoc();
        $precioUnitario = $producto['precio'];
        $subtotal = $precioUnitario * $cantidad;
        // Me guardo la fecha de hoy en formato Date
        $fechaHoy = date("Y-m-d");
        // Aquí hago una consulta para verificar la comra
        $verificarCompra = $conexionBaseDatos->prepare("SELECT * FROM Compran WHERE dni = ? AND cod_producto = ? AND DATE(fecha_compra) = ?");
        $verificarCompra->bind_param("sss", $dniCliente, $codProducto, $fechaHoy);
        $verificarCompra->execute();
        $resultadoCompra = $verificarCompra->get_result();

        if ($resultadoCompra->num_rows > 0) {
            /* Si el numero de filas es mayor que 0 quiere decir que ese usuario ya ha comprado ese producto en el mismo día de la fecha del 
            sistema por lo que no le permito volver a comprar ese producto hasta el día siguiente a la fecha que ya tenía */
            $_SESSION['error'] = "Este producto ya ha sido comprado recientemente. Vuelva otro día.";
        } else {
            /* Si el número de filas es 0 quiere decir que ese usuario no ha comprado ese producto en el mismo día de la fecha del sistema por
            lo que le permito que pueda comprar ese producto */
            $insertarCompra = $conexionBaseDatos->prepare("INSERT INTO Compran (dni, cod_producto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
            $insertarCompra->bind_param("ssid", $dniCliente, $codProducto, $cantidad, $subtotal);

            if ($insertarCompra->execute()) {
                if ($insertarCompra->affected_rows > 0) {
                    /* Si la inserción se ha ejecutado y el número de filas afectadas es mayor que 0 quiere decir que la compra se ha realizado
                    correctamente */
                    $_SESSION['mensaje'] = "¡Compra realizada con éxito!";
                } else {
                    /* Si la inserción se ha ejecutado pero el número de filas es 0 quiere decir que no se ha realizado la compra */
                    $_SESSION['error'] = "No se pudo completar la compra. Intentalo de nuevo.";
                }
            } else {
                // Si la ejecución ha fallado, muestro un mensaje de error
                $_SESSION['error'] = "Error al registrar la compra.";
            }
            // Cierro la consulta de inserción
            $insertarCompra->close();
        }
        // Cierro la consulta de la verificación de compra
        $verificarCompra->close();
    } else {
        // Si no ha encontrado el producto le muestro un mensaje de error
        $_SESSION['error'] = "Producto no encontrado.";
    }
    // Cierro la consulta del precio del producto
    $consultaPrecio->close();
    // Cierro conexión con la base de datos
    Conexion::cerrarConexionBD();
    // Redirijo al origen de donde proviene el formulario
    header('Location: ../' . $origen .'.php');
    exit();
} 
