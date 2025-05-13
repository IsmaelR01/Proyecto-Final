<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

// Solo permitir admins
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cif = validarCif(filter_input(INPUT_POST, 'CIF'));
    $nombreProveedor = validarNombre(filter_input(INPUT_POST, 'nombre_proveedor'));
    $direccionProveedor = validarDireccion(filter_input(INPUT_POST, 'direccion_proveedor'));
    $telefonoProveedor = validarTelefono(filter_input(INPUT_POST, 'telefono'));

    $conexionBaseDatos = Conexion::conexionBD();

    if (!$cif || !$nombreProveedor || !$direccionProveedor || !$telefonoProveedor) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: ../quienesSomos.php');
        exit();
    } else {
        $editarProveedor = $conexionBaseDatos->prepare("UPDATE Proveedores SET nombre_proveedor = ?, direccion_proveedor = ?, telefono = ? WHERE CIF = ?");
        $editarProveedor->bind_param("ssss", $nombreProveedor, $direccionProveedor, $telefonoProveedor, $cif);

        if ($editarProveedor->execute()) {
            if ($editarProveedor->affected_rows > 0) {
                $_SESSION['mensaje'] = "Proveedor editado correctamente.";
            } else {
                $_SESSION['error'] = "No se realizaron cambios porque los datos de los campos son iguales.";
            }
        } else {
            $_SESSION['error'] = "Error al editar el proveedor";
        }

        header('Location: ../quienesSomos.php');
        exit();
    }
}
?>
