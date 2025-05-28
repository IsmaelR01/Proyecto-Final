<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

// Si no existe la sesión del usuario y su rol no es admin lo redirijo a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
// Compruebo si existe la variable del campo oculto que en este caso es el CIF
if (filter_has_var(INPUT_POST, 'CIF')) {
    //valido todos los campos incluido el campo oculto con funciones de validacion para evitar inyecciones XSS o html
    $cif = validarCif(filter_input(INPUT_POST, 'CIF'));
    $nombreProveedor = validarNombre(filter_input(INPUT_POST, 'nombre_proveedor'));
    $direccionProveedor = validarDireccion(filter_input(INPUT_POST, 'direccion_proveedor'));
    $telefonoProveedor = validarTelefono(filter_input(INPUT_POST, 'telefono'));
    // Creo una variable para hacer conexión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();
    // Si alguno de los campos no es correcto le muestro un mensaje de error
    if (!$cif || !$nombreProveedor || !$direccionProveedor || !$telefonoProveedor) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
    } else {
        // Una vez que los campos son válidos, procedo a hacer la actualización del proveedor
        $editarProveedor = $conexionBaseDatos->prepare("UPDATE Proveedores SET nombre_proveedor = ?, direccion_proveedor = ?, telefono = ? WHERE CIF = ?");
        $editarProveedor->bind_param("ssss", $nombreProveedor, $direccionProveedor, $telefonoProveedor, $cif);

        if ($editarProveedor->execute()) {
            if ($editarProveedor->affected_rows > 0) {
                /* Si la actualización se ha ejecutado correctamente y el número de filas afectadas es mayor que 0 quiere decir que la 
                actualización en la base de datos se hizo correctamente por lo que mostraré un mensaje de exito */
                $_SESSION['mensaje'] = "Proveedor editado correctamente.";
            } else {
                /* Si la actualización se ha ejecutado correctamente pero el número de filas afectadas es 0 quiere decir que la 
                actualización en la base de datos no se ha producido por lo que mostraré un mensaje de error */
                $_SESSION['error'] = "No se realizaron cambios porque los datos de los campos son iguales.";
            }
        } else {
            // Si la ejecución de la actualización falla, mostraré un mensaje de error
            $_SESSION['error'] = "Error al editar el proveedor.";
        }

        // Cierro la consulta de actualización
        $editarProveedor->close();
    }
    // Cierro la conexión con la base de datos 
    Conexion::cerrarConexionBD();
    // Redirijo al archivo quienes somos con el mensaje correspondiente
    header('Location: ../quienesSomos.php');
    exit();
}
?>
