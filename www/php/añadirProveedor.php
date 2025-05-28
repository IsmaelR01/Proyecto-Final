<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';
// Si no existe la sesión del usuario y su rol no es admin, lo redirio a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
// Compruebo si existe la variable del campo de envio, en este caso, es el botón de envio del formulario
if (filter_has_var(INPUT_POST, 'añadirEnviarProveedor')) {
    // valido los campos con funciones de validación, para evitar inyecciones XSS O html
    $cifProveedor = validarCif(filter_input(INPUT_POST, 'CIF'));
    $nombreProveedor = validarNombre(filter_input(INPUT_POST, 'nombre_proveedor'));
    $direccionProveedor = validarDireccion(filter_input(INPUT_POST, 'direccion_proveedor'));
    $telefonoProveedor = validarTelefono(filter_input(INPUT_POST, 'telefono'));
    // Declaro la variable para la conexión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();
    // Si alguno de los campos no son correctos le muestro un mensaje de error redirigiendolo hacia quienesSomos.php
    if (!$cifProveedor || !$nombreProveedor || !$direccionProveedor || !$telefonoProveedor) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: ../quienesSomos.php');
        exit();
    } else {
        // Primero compruebo si ya existe un proveedor con ese CIF
        $consultaCif = $conexionBaseDatos->prepare("SELECT CIF FROM Proveedores WHERE CIF = ?");
        $consultaCif->bind_param("s", $cifProveedor);
        $consultaCif->execute();
        $resultado = $consultaCif->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            // Si ya existe un proveedor con ese CIF muestro un mensaje de error y lo reidirijo a quienesSomos.php
            $_SESSION['error'] = "Ya existe un proveedor con ese CIF.";
            $consultaCif->close();
            Conexion::cerrarConexionBD();
            header('Location: ../quienesSomos.php');
            exit();
        }
        $consultaCif->close();

        // Si los campos son validos procedo con la inserción del proveedor en la base de datos
        $insertarProveedor = $conexionBaseDatos->prepare("INSERT INTO Proveedores (CIF, nombre_proveedor, direccion_proveedor, telefono) VALUES (?, ?, ?, ?)");
        $insertarProveedor->bind_param("ssss", $cifProveedor, $nombreProveedor, $direccionProveedor, $telefonoProveedor);

        if ($insertarProveedor->execute()) {
            if ($insertarProveedor->affected_rows > 0) {
                // Si la inserción se ha ejecutado y las filas afectadas es mayor que 0 quiere decir que se ha añadido correctamente
                $_SESSION['mensaje'] = "Proveedor añadido correctamente.";
            } else {
                // Si la inserción se ha ejecutado y las filas afectadas es 0 quiere decir que no se ha añadido ningún proveedor
                $_SESSION['error'] = "No se ha añadido ningún proveedor";
            }
        } else {
            // Si la inserción ha fallado le muestro un mensaje de error
            $_SESSION['error'] = "Error al ejecutar la inserción del proveedor.";
        }

        // Cerrar consulta y conexión
        $insertarProveedor->close();
        Conexion::cerrarConexionBD();
        // Aquí redirijo al archivo quienesSomos.php 
        header('Location: ../quienesSomos.php');
        exit();
    }
}
