<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

// Si no existe la sesión del usuario y su rol no es admin lo redirijo a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
// Compruebo si existe la variable del campo que en este caso es el botón enviar del formulario
if (filter_has_var(INPUT_POST, 'botonEditarEnviar')) {
    // valido todos los campos con funciones de validacion para evitar inyecciones XSS o html
    $dniUsuarioSeleccionado = validarDni(filter_input(INPUT_POST, 'seleccionUsuario'));
    $nombre = validarUsuario(filter_input(INPUT_POST, 'nombre'));
    $email = validarEmail(filter_input(INPUT_POST, 'email'));
    $direccion = validarDireccion(filter_input(INPUT_POST, 'direccion'));
    // Creo una variable para hacer conexión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();
    // Si alguno de los campos no es correcto le muestro un mensaje de error
    if (!$dniUsuarioSeleccionado || !$nombre || !$email || !$direccion) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
    } else {
        // Comprobar si el nombre de usuario ya existe para otro DNI distinto, si existe el usuario le muestro un mensaje de error
        $consultaNombreUsuario = $conexionBaseDatos->prepare("SELECT dni FROM Usuarios WHERE nombre_usuario = ? AND dni != ?");
        $consultaNombreUsuario->bind_param("ss", $nombre, $dniUsuarioSeleccionado);
        $consultaNombreUsuario->execute();
        $resultadoNombreUsuario = $consultaNombreUsuario->get_result();

        if ($resultadoNombreUsuario->num_rows > 0) {
            $_SESSION['error'] = "El nombre de usuario ya está en uso por otro usuario.";
            $consultaNombreUsuario->close();
            Conexion::cerrarConexionBD();
            $_SESSION['accion'] = 'editar';
            $_SESSION['seleccionUsuario'] = $dniUsuarioSeleccionado;
            header('Location: administrarUsuarios.php');
            exit();
        }
        $consultaNombreUsuario->close();

        // Si los campos son correctos procedo con la actualización del usuario
        $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, email = ?, direccion = ? WHERE dni = ?");
        $editarUsuario->bind_param("ssss", $nombre, $email, $direccion, $dniUsuarioSeleccionado);

        if ($editarUsuario->execute()) {
            if ($editarUsuario->affected_rows > 0) {
                /* Si la actualización se ha ejecutado correctamente y el número de filas afectadas es mayor que 0 quiere decir que la 
                actualización en la base de datos se hizo correctamente por lo que mostraré un mensaje de exito */
                $_SESSION['mensaje'] = "Usuario editado correctamente.";
            } else {
                /* Si la actualización se ha ejecutado correctamente pero el número de filas afectadas es 0 quiere decir que la 
                actualización en la base de datos no se ha producido por lo que mostraré un mensaje de error */
                $_SESSION['error'] = "No se realizaron cambios porque los datos de los campos son iguales.";
            }
        } else {
            // Si la ejecución de la actualización falla, mostraré un mensaje de error
            $_SESSION['error'] = "Error al editar el usuario.";
        }
        // Cierro la consulta de actualización
        $editarUsuario->close();
    }
    // Cierro la conexión con la base de datos
    Conexion::cerrarConexionBD();
    // Guardo la acción de editar y el usuario seleccionado en sesiones
    $_SESSION['accion'] = 'editar';
    $_SESSION['seleccionUsuario'] = $dniUsuarioSeleccionado;
    //Redirijo a administrarUsuarios.php
    header('Location: administrarUsuarios.php');
    exit();
}
