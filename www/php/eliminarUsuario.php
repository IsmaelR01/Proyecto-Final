<?php
session_start();
require_once 'Conexion.php';
include_once 'funciones_validar.php';
// Si no existe la sesión del usuario y su rol no es admin lo redirijo a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
//Compruebo si existe la variable del campo, que en este caso es el botón enviar que viene del formulario
if (filter_has_var(INPUT_POST, 'botonEliminarEnviar')) {
    // Valido el campo oculto con funciones de validación para evitar inyecciones XSS y html
    $dniUsuarioSeleccionado = validarDni(filter_input(INPUT_POST, 'seleccionUsuario'));
    // Creo una variable para hacer la conexión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();
    // Si el campo no es correcto muestro un mensaje de error
    if (!$dniUsuarioSeleccionado) {
        $_SESSION['error'] = "El campo no es válido";
        $_SESSION['accion'] = 'eliminar';
        $_SESSION['seleccionUsuario'] = $dniUsuarioSeleccionado;
        header('Location: administrarUsuarios.php');
        exit();
    } else {
        // Una vez comprobado que el campo es correcto procedo con la consulta de eliminación del usuario
        $eliminarUsuario = $conexionBaseDatos->prepare("DELETE FROM Usuarios WHERE dni = ?");
        $eliminarUsuario->bind_param("s", $dniUsuarioSeleccionado);

        if ($eliminarUsuario->execute()) {
            if ($eliminarUsuario->affected_rows > 0) {
                /* Si la eliminación se ha ejecutado correctamente y el número de filas afectadas es mayor que 0 quiere decir que la 
                eliminación en la base de datos se hizo correctamente por lo que mostraré un mensaje de exito */
                /* Si existe la sesión con el dni del usuario logueado y además coincide con el usuario seleccionado, quiere decir que ese usuario
                se ha seleccionado a sí mismo por lo que directamente se hace su eliminación de forma correcta y automáticamente lo redirijo
                a logout.php para destruir su sesión inmediatamente y a su vez se redirigirá al index.php y si vuelve a intentar iniciar sesión
                no le dejará puesto que se ha eliminado a sí mismo */
                if (isset($_SESSION['identificadorUsuario']) && $_SESSION['identificadorUsuario'] === $dniUsuarioSeleccionado) {
                    $eliminarUsuario->close();
                    Conexion::cerrarConexionBD();
                    header('Location: logout.php');
                    exit();
                }
                $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
            } else {
                /* Si la eliminación se ha ejecutado correctamente pero el número de filas afectadas es 0 quiere decir que la 
                eliminación en la base de datos no se ha producido por lo que mostraré un mensaje de error */
                $_SESSION['error'] = "No se ha eliminado ningún usuario. El DNI no existe.";
            }
        } else {
            // Si la ejecución de la eliminación falla, mostraré un mensaje de error
            $_SESSION['error'] = "Error al ejecutar la eliminación del usuario.";
        }

        // Cierro la consulta de eliminación
        $eliminarUsuario->close();
    }
    // Cierro la conexión con la base de datos
    Conexion::cerrarConexionBD();
    // Guardo la acción y el usuario seleccionado y redirijo a administrarUsuarios.php
    $_SESSION['accion'] = 'eliminar';
    $_SESSION['seleccionUsuario'] = $dniUsuarioSeleccionado;
    header('Location: administrarUsuarios.php');
    exit();
}
