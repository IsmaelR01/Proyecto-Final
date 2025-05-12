<?php
session_start();
include_once 'funciones_validar.php';

require_once 'Conexion.php';

$conexionBaseDatos = Conexion::conexionBD();

if (filter_has_var(INPUT_POST, 'sesion')) {
    $usuario = validarUsuario(filter_input(INPUT_POST, 'usuario'));
    $contrasena = filter_input(INPUT_POST, 'contrasena');

    if (!$usuario || !$contrasena) {
        $mensaje = "El usuario o la contraseña no cumplen con los requisitos o los campos están vacíos.";
    } else {
        try {
            $consultaUsuario = $conexionBaseDatos->prepare("SELECT u.dni, u.nombre_usuario, r.tipo AS usuarioRol, u.clave FROM Usuarios AS u INNER JOIN Roles AS r ON u.id_rol = r.id_rol WHERE u.nombre_usuario = ?");
            $consultaUsuario->bind_param("s", $usuario);  
            $consultaUsuario->execute();
            $resultado = $consultaUsuario->get_result();
            
            if ($resultado->num_rows > 0) {
                $usuarioBaseDatos = $resultado->fetch_assoc();
                $contrasenaCifrada = $usuarioBaseDatos['clave'];

                if (password_verify($contrasena, $contrasenaCifrada)) {
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['acceso'] = time() + 1200; 
                    
                    $rol = $usuarioBaseDatos['usuarioRol'];
                    $_SESSION['rol'] = $rol;
                    $identificadorUsuario = $usuarioBaseDatos['dni'];
                    $_SESSION['identificadorUsuario'] = $identificadorUsuario;
                    switch ($rol) {
                        case 'admin':
                            header('Location: ../index.php');
                            break;
                        case 'cliente':
                            header('Location: ../index.php');
                            break;
                    }
                } else {
                    $mensaje = "El usuario o la contraseña no son correctos.";
                }
            } else {
                $mensaje = "El usuario no está registrado. Por favor, regístrate primero.";
            }
            $consultaUsuario->close();
            Conexion::cerrarConexionBD();
        } catch (Exception $e) {
            $mensaje = "ERROR: " . $e->getMessage();
        }
    }
}

if (isset($_SESSION['usuario'])) {
    if (time() - $_SESSION['acceso'] > 1200) { 
        session_destroy();
        $mensaje = "La sesión ha expirado por inactividad. Debes volver a iniciar sesión.";
    } else {
        $_SESSION['acceso'] = time() + 1200; 
    }
}
if (isset($mensaje)) {
    $_SESSION['mensaje_error'] = $mensaje;
    header('Location: login.php');
    exit;
} ?>