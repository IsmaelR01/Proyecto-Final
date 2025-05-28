<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

// Si no existe la sesión del usuario y su rol no es admin lo redirijo a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
// Compruebo si existen las variables de los campos, que en este caso son los campos ocultos que vienen del formulario
if (filter_has_var(INPUT_POST, 'origen') && filter_has_var(INPUT_POST, 'cod_producto')) {
    $origen = validarCadena(filter_input(INPUT_POST, 'origen'));
    $cod_producto = validarCodigoProducto(filter_input(INPUT_POST, 'cod_producto'));
    // Creo una variable para hacer conexión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();
    // Creo una variable con los orígenes permitidos
    $origenesPermitidos = ['jerseys', 'chaquetas'];
    // Si el origen no es válido o no está en el array de permitidos lo redirijo a la página principal
    if (!in_array($origen, $origenesPermitidos) || !$origen) {
        $_SESSION['error'] = 'Origen desconocido';
        header("Location: ../index.php");
        exit();
    }
    // Si el campo no es válido muestro un mensaje de error
    if (!$cod_producto) {
        $_SESSION['error'] = "El campo no es válido";
        header('Location: ../' . $origen . '.php');
        exit();
    } else {
        // Si el campo es válido hago la consulta de eliminación
        $eliminarProducto = $conexionBaseDatos->prepare("DELETE FROM Productos WHERE cod_producto = ?");
        $eliminarProducto->bind_param("s", $cod_producto);

        if ($eliminarProducto->execute()) {
            if ($eliminarProducto->affected_rows > 0) {
                /* Si la eliminación se ha ejecutado correctamente y el número de filas afectadas es mayor que 0 quiere decir que la 
                eliminación en la base de datos se hizo correctamente por lo que mostraré un mensaje de exito */
                $_SESSION['mensaje'] = "Producto eliminado correctamente.";
            } else {
                /* Si la eliminación se ha ejecutado correctamente pero el número de filas afectadas es 0 quiere decir que la 
                eliminación en la base de datos no se ha producido por lo que mostraré un mensaje de error */
                $_SESSION['error'] = "No se ha eliminado ningún producto. Es posible que el código no exista.";
            }
        } else {
            // Si la ejecución de la eliminación falla, mostraré un mensaje de error
            $_SESSION['error'] = "Error al ejecutar la eliminación del producto.";
        }

        // Cierro la consulta de eliminación
        $eliminarProducto->close();
    }
    // Cierro la conexión con la base de datos
    Conexion::cerrarConexionBD();
    // Redirijo al origen de donde proviene el envio del formulario
    header('Location: ../' . $origen . '.php');
    exit();
}
