<?php
session_start();
include_once 'funciones_validar.php';

require_once 'Conexion.php';
// Creo una variable con la conexióon a la base de datos
$conexionBaseDatos = Conexion::conexionBD();
// Verfiico si existe la variable en el formulario, que en este caso es la del boón de enviar
if (filter_has_var(INPUT_POST, 'sesion')) {
    // Hago las validacines para los campos con funciones de validación
    $usuario = validarUsuario(filter_input(INPUT_POST, 'usuario'));
    $contrasena = validarContrasena(filter_input(INPUT_POST, 'contrasena'));
    // Si uno o ambos campos no se cumplen le muestro un mensaje de error
    if (!$usuario || !$contrasena) {
        $mensaje = "El usuario o la contraseña no cumplen con los requisitos o los campos están vacíos.";
    } else {
        // Si los campos son válidos hago una consulta para ver si el usuario existe en la base de datos
        try {
            $consultaUsuario = $conexionBaseDatos->prepare("SELECT u.dni, u.nombre_usuario, r.tipo AS usuarioRol, u.clave, u.perfil FROM Usuarios AS u INNER JOIN Roles AS r ON u.id_rol = r.id_rol WHERE u.nombre_usuario = ?");
            $consultaUsuario->bind_param("s", $usuario);  
            $consultaUsuario->execute();
            $resultado = $consultaUsuario->get_result();
            
            if ($resultado->num_rows > 0) {
                /* Si el usuario existe en la base de datos procedo a verificar la contraseña introducida por el usuario con la contraseña 
                cifrada en la base de datos */
                $usuarioBaseDatos = $resultado->fetch_assoc();
                $contrasenaCifrada = $usuarioBaseDatos['clave'];

                if (password_verify($contrasena, $contrasenaCifrada)) {
                    /* Si las contraseñas coinciden, me guardo del usuario en las sesiones, el dni, nombre de usuario, su foto de perfil 
                    y el rol que tiene en la página, es decir, si el usuario es cliente o administrador y le dejo que pueda tener una sesión 
                    activa durante 20 minutos */

                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['acceso'] = time() + 1200; 
                    
                    $rol = $usuarioBaseDatos['usuarioRol'];
                    $_SESSION['rol'] = $rol;
                    $identificadorUsuario = $usuarioBaseDatos['dni'];
                    $_SESSION['identificadorUsuario'] = $identificadorUsuario;
                    // Si no tiene foto de perfil, le pongo la foto de perfil por defecto que tiene la base de datos
                    $_SESSION['perfil'] = $usuarioBaseDatos['perfil'] ?? 'imagenes/fotosPerfil/emoticono.jpg';
                    // Aquí redirijimos al usuario a la página principal, pero con sus privilegios correspondientes
                    switch ($rol) {
                        case 'admin':
                            header('Location: ../index.php');
                            break;
                        case 'cliente':
                            header('Location: ../index.php');
                            break;
                    }
                } else {
                    // Si el usuario o la contrasseña no coinciden con los de la base de datos le muestro un mensaje de error
                    $mensaje = "El usuario o la contraseña no son correctos.";
                }
            } else {
                // Si no existe el usuario en la base de datos, le muestro un mensaje de error
                $mensaje = "El usuario no está registrado. Por favor, regístrate primero.";
            }
            // Cierro la consulta y tqambién cierro la conexión con la base de datos
            $consultaUsuario->close();
            Conexion::cerrarConexionBD();
        } catch (Exception $e) {
            //Capturo el mensaje de error si ocurre un error inespaerado
            $mensaje = "ERROR: " . $e->getMessage();
        }
    }
}
// aquí si existe la sesión y si supera los 20 minutos, la sesión se cerrará por inactividad
if (isset($_SESSION['usuario'])) {
    if (time() - $_SESSION['acceso'] > 1200) { 
        session_destroy();
        $mensaje = "La sesión ha expirado por inactividad. Debes volver a iniciar sesión.";
    } else {
        // Si vuelve a tener actividad dentro de esos 20 minutos, se le volverán a sumar otros 20 minutos a la sesión 
        $_SESSION['acceso'] = time() + 1200; 
    }
}
// Si existen los mensajes de error los muestro en el login.php
if (isset($mensaje)) {
    $_SESSION['mensaje_error'] = $mensaje;
    header('Location: login.php');
    exit;
} ?>