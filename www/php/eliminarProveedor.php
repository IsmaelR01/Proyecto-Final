<?php
session_start();
require_once 'Conexion.php';
include_once 'funciones_validar.php';

// Si no existe la sesión del usuario y su rol no es admin lo redirijo a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Compruebo si existe la variable del campo, que en este caso es el campo oculto que viene del formulario
if (filter_has_var(INPUT_POST, 'CIF')) {
    // Valido el campo oculto con funciones de validación para evitar inyecciones XSS o HTML
    $cif = validarCif(filter_input(INPUT_POST, 'CIF'));

    // Creo una variable para hacer conexión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();

    // Si el campo no es correcto le muestro un mensaje de error
    if (!$cif) {
        $_SESSION['error'] = "El campo no es válido";
    } else {
        // Compruebo si el proveedor tiene productos asociados antes de intentar eliminarlo
        $consultaProductos = $conexionBaseDatos->prepare("SELECT COUNT(*) AS total FROM Productos WHERE CIF = ?");
        $consultaProductos->bind_param("s", $cif);
        $consultaProductos->execute();
        $resultado = $consultaProductos->get_result();
        $resultadoProductos = $resultado->fetch_assoc();
        $totalProductos = $resultadoProductos['total'];
        $consultaProductos->close();

        if ($totalProductos > 0) {
            /* Si el proveedor tiene productos asignados entonces no se puede eliminar.
            Para borrar el proveedor primero se tienen que eliminar todos los productos que tenga asignados */
            $_SESSION['error'] = "No se puede eliminar el proveedor porque tiene productos asignados.";
            header('Location: ../quienesSomos.php');
            exit();
        } else {
            // Si el proveedor no tiene productos asignados procedo con la eliminación
            $eliminarProveedor = $conexionBaseDatos->prepare("DELETE FROM Proveedores WHERE CIF = ?");
            $eliminarProveedor->bind_param("s", $cif);
            $eliminarProveedor->execute();

            if ($eliminarProveedor->affected_rows > 0) {
                // Si el número de filas afectadas es mayor que 0 quiere decir que se ha eliminado correctamente.
                $_SESSION['mensaje'] = "Proveedor eliminado correctamente.";
            } else {
                // Si el número de filas afectadas es 0 quiere decir que no se ha eliminado correctamente.
                $_SESSION['error'] = "No se ha eliminado ningún proveedor. Es posible que el CIF no exista.";
            }

            // Cierro la consulta de eliminación
            $eliminarProveedor->close();
        }
    }

    // Cierro la conexión con la base de datos
    Conexion::cerrarConexionBD();

    // Redirijo al archivo quienesSomos.php
    header('Location: ../quienesSomos.php');
    exit();
}
?>
