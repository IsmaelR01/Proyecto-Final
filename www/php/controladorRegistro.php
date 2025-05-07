<?php
session_start();
include_once 'funciones_validar.php';
require_once 'Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Primero validamos los datos ingresados
    $dni = validarDni(filter_input(INPUT_POST, 'dni'));
    $usuario = validarUsuario(filter_input(INPUT_POST, 'usuario'));
    $direccion = validarDireccion(filter_input(INPUT_POST, 'direccion'));
    $email = validarEmail(filter_input(INPUT_POST, 'email'));
    $contrasena = validarContrasena(filter_input(INPUT_POST, 'contrasena'));

    if (!$dni || !$usuario || !$email || !$direccion || !$contrasena) {
        // Si los datos no son válidos, redirige directamente
        $mensaje = "Los datos que has introducido no son válidos";
    } else {
        try {
            // Preparamos la consulta para verificar si el usuario ya existe
            $consultaUsuario = $conexionBaseDatos->prepare("SELECT nombre_usuario FROM Usuarios WHERE nombre_usuario = ?");
            $consultaUsuario->bind_param("s", $usuario); // "s" para string
            $consultaUsuario->execute();
            $resultado = $consultaUsuario->get_result();

            if ($resultado->num_rows > 0) {
                // Usuario ya existe
                $mensaje = "El usuario ya está registrado";
            } else {
                // Si el usuario no existe, procedemos a registrarlo
                $contrasenaCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                if(isset($_SESSION['usuario']) && $_SESSION['rol'] === "admin") {
                    $insertarUsuario = $conexionBaseDatos->prepare("INSERT INTO Usuarios (dni,nombre_usuario, clave, email, direccion, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
                    $idRol = 1;
                    $insertarUsuario->bind_param("sssssi",$dni ,$usuario, $contrasenaCifrada, $email, $direccion,$idRol); 
                } else {
                    $insertarUsuario = $conexionBaseDatos->prepare("INSERT INTO Usuarios (dni,nombre_usuario, clave, email, direccion, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
                    $idRol = 2;
                    $insertarUsuario->bind_param("sssssi",$dni ,$usuario, $contrasenaCifrada, $email, $direccion,$idRol); 
                }

                $insertarUsuario->execute();
                

                // Verificamos si se insertó correctamente
                if ($insertarUsuario->affected_rows > 0) {
                    $mensajeExito = "Usuario registrado correctamente";
                } else {
                    $mensaje = "Hubo un error al registrar el usuario";
                }
            }
            // Cerrar consultas y conexión
            $consultaUsuario->close();
            if(isset($insertarUsuario)) {
                $insertarUsuario->close();
            }
            Conexion::cerrarConexionBD();
        } catch(Exception $e){
            // Captura el error y lo muestra
            $mensaje = "Error en la ejecución: " . $e->getMessage();
        }
    }
    
    if (isset($mensaje)) {
        $_SESSION['mensajeExito'] = $mensajeExito;
    } else if(isset($mensajeExito)) {
        $_SESSION['mensaje_error'] = $mensaje;
    }
    header('Location: registro.php');
    exit();
}
?>
