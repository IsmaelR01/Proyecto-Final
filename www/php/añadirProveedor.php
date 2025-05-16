<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cifProveedor = validarCif(filter_input(INPUT_POST, 'CIF'));
    $nombreProveedor = validarNombre(filter_input(INPUT_POST, 'nombre_proveedor'));
    $direccionProveedor = validarDireccion(filter_input(INPUT_POST, 'direccion_proveedor'));
    $telefonoProveedor = validarTelefono(filter_input(INPUT_POST, 'telefono'));

    $conexionBaseDatos = Conexion::conexionBD();
    
    if (!$cifProveedor || !$nombreProveedor || !$direccionProveedor || !$telefonoProveedor) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: ../quienesSomos.php');
        exit();
    } else {
        $insertarProveedor = $conexionBaseDatos->prepare("INSERT INTO Proveedores (CIF, nombre_proveedor, direccion_proveedor, telefono) VALUES (?, ?, ?, ?)");
        $insertarProveedor->bind_param("ssss", $cifProveedor, $nombreProveedor, $direccionProveedor, $telefonoProveedor);

        if ($insertarProveedor->execute()) {
            if ($insertarProveedor->affected_rows > 0) {
                $_SESSION['mensaje'] = "Proveedor añadido correctamente.";
            } else {
                $_SESSION['error'] = "No se ha añadido ningún proveedor. El CIF ya existe.";
            }
        } else {
            $_SESSION['error'] = "Error al ejecutar la inserción del proveedor.";
        }

        // Cerrar consulta y conexión
        $insertarProveedor->close();
        Conexion::cerrarConexionBD();

        header('Location: ../quienesSomos.php');
        exit();
    }
}
?>
