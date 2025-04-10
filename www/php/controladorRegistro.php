<?php
include_once 'funciones_validar.php';
require_once 'Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Primero validamos los datos ingresados
    $dni = validarDni(filter_input(INPUT_POST, 'dni'));
    $usuario = validarUsuario(filter_input(INPUT_POST, 'usuario'));
    $email = validarEmail(filter_input(INPUT_POST, 'email'));
    $contrasena = validarContrasena(filter_input(INPUT_POST, 'contrasena'));

    if (!$dni || !$usuario || !$email || !$contrasena) {
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
                // Preparamos la consulta para insertar un nuevo usuario
                $insertarUsuario = $conexionBaseDatos->prepare("INSERT INTO Usuarios (dni,nombre_usuario, clave, id_rol) VALUES (?, ?, ?, ?)");
                $idRol = 2;
                $insertarUsuario->bind_param("sssi",$dni ,$usuario, $contrasenaCifrada, $idRol); 
                $insertarUsuario->execute();

                // Verificamos si se insertó correctamente
                if ($insertarUsuario->affected_rows > 0) {
                    $mensaje = "Usuario registrado correctamente";
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
    
    // Mostrar el mensaje resultante
    if(isset($mensaje)) {
        echo $mensaje;
    }
}
?>
