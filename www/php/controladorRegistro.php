<?php
session_start();
include_once 'funciones_validar.php';
require_once 'Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
// Compruebo si existe la variable en el formulario, en este caso, es el botón del envio del formulario
if (filter_has_var(INPUT_POST, 'registrar')) {
    // Aquí valido los campos del formulario con funciones de validación
    $dni = validarDni(filter_input(INPUT_POST, 'dni'));
    $usuario = validarUsuario(filter_input(INPUT_POST, 'usuario'));
    $direccion = validarDireccion(filter_input(INPUT_POST, 'direccion'));
    $email = validarEmail(filter_input(INPUT_POST, 'email'));
    $contrasena = validarContrasena(filter_input(INPUT_POST, 'contrasena'));

    if (!$dni || !$usuario || !$email || !$direccion || !$contrasena) {
        // Si los datos no son válidos, muestro un mensaje de error
        $mensaje = "Los datos que has introducido no son válidos";
    } else {
        try {
            // Si todos los campos son válidos, hago la consulta para verificar si el usuario ya existe
            $consultaUsuario = $conexionBaseDatos->prepare("SELECT nombre_usuario FROM Usuarios WHERE nombre_usuario = ?");
            $consultaUsuario->bind_param("s", $usuario); 
            $consultaUsuario->execute();
            $resultado = $consultaUsuario->get_result();

            if ($resultado->num_rows > 0) {
                // Si el numerod de filas devueltas es mayor que 0 quiere decir que el usuario ya está registrado y le muesro un mensaje
                $mensaje = "El usuario ya está registrado";
            } else {
                // Si el usuario no existe, procedo a registrarlo, primero cifro la contraseña en la base de datos con password_hash
                $contrasenaCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                // Si el usuario es un administrador, él podra registrar a usuarios solo con privilegios de administrador
                if(isset($_SESSION['usuario']) && $_SESSION['rol'] === "admin") {
                    $insertarUsuario = $conexionBaseDatos->prepare("INSERT INTO Usuarios (dni,nombre_usuario, clave, email, direccion, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
                    $idRol = 1;
                    $insertarUsuario->bind_param("sssssi",$dni ,$usuario, $contrasenaCifrada, $email, $direccion,$idRol); 
                } else {
                    /* Aquí lo que hago es que si se está registrando un usuario, no existe ni la sesión de usuario y además no tiene los
                    privilegios de administrador, entonces el usuario que se va a registrar va a ser un cliente */
                    $insertarUsuario = $conexionBaseDatos->prepare("INSERT INTO Usuarios (dni,nombre_usuario, clave, email, direccion, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
                    $idRol = 2;
                    $insertarUsuario->bind_param("sssssi",$dni ,$usuario, $contrasenaCifrada, $email, $direccion,$idRol); 
                }
                // Ejecuto la inserción
                $insertarUsuario->execute();
                

                // Verifico si se insertó correctamente
                if ($insertarUsuario->affected_rows > 0) {
                    // Si las filas afctadas son mayor que 0 entonces se ha registrado correctamente y le muestro un mensaje al usuario
                    $mensajeExito = "Usuario registrado correctamente";
                } else {
                    // Si las filas afectadas son 0 entonces ha habido un error en la inserción y le muestro el mensaje al usuario
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
            // Capturo el error y lo muestro
            $mensaje = "Error en la ejecución: " . $e->getMessage();
        }
    }
    // Si exite uno u otro mensaje me lo guardo en sesiones para mostrarselo al usuario en registro.php y lo redirijo con el header
    if (isset($mensaje)) {
        $_SESSION['mensaje_error'] = $mensaje;
    } else if(isset($mensajeExito)) {
        $_SESSION['mensajeExito'] = $mensajeExito;
    }
    header('Location: registro.php');
    exit();
}
?>
