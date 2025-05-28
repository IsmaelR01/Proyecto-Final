<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';
// Si no existe el identificador del usuario lo reirijo a la página principal
if (!isset($_SESSION['identificadorUsuario'])) {
    header('Location: ../index.php');
    exit();
}
// Hago una función para comprobar si el usuario existe
function usuarioYaExiste($conexion, $nombreUsuario, $dniUsuarioSeleccionado) {
    $consultaUsuarioExiste = $conexion->prepare("SELECT COUNT(*) FROM Usuarios WHERE nombre_usuario = ? AND dni != ?");
    $consultaUsuarioExiste->bind_param("ss", $nombreUsuario, $dniUsuarioSeleccionado);
    $consultaUsuarioExiste->execute();
    $resultado = $consultaUsuarioExiste->get_result();
    $existe = $resultado->fetch_row()[0];
    $consultaUsuarioExiste->close();
    return $existe > 0;
}
// Hago una función para obtener la contraseña cifrada de la base de dtos
function obtenerContrasenaCifrada($conexion, $dniUsuario) {
    $consultaContrasenaActual = $conexion->prepare("SELECT clave FROM Usuarios WHERE dni = ?");
    $consultaContrasenaActual->bind_param("s", $dniUsuario);
    $consultaContrasenaActual->execute();
    $resultadoContrasenaActual = $consultaContrasenaActual->get_result();
    $claveCifrada = '';
    if ($resultadoContrasenaActual->num_rows > 0) {
        $fila = $resultadoContrasenaActual->fetch_assoc();
        $claveCifrada = $fila['clave'];
    }
    $consultaContrasenaActual->close();
    return $claveCifrada;
}
/* Hago una función para actualizar el usuario y meto las diferentes condiciones ya que hay que tener en cuenta
todos los casos debido a que hay campos opcionales que pueden o no rellenarse */
function actualizarUsuario($conexion, $dni, $nombreUsuario, $direccion, $clave = '', $imagen = '') {
    if ($clave !== '' && $imagen !== '') {
        $editarUsuario = $conexion->prepare("UPDATE Usuarios SET nombre_usuario = ?, clave = ?, direccion = ?, perfil = ? WHERE dni = ?");
        $editarUsuario->bind_param("sssss", $nombreUsuario, $clave, $direccion, $imagen, $dni);
    } elseif ($clave !== '') {
        $editarUsuario = $conexion->prepare("UPDATE Usuarios SET nombre_usuario = ?, clave = ?, direccion = ? WHERE dni = ?");
        $editarUsuario->bind_param("ssss", $nombreUsuario, $clave, $direccion, $dni);
    } elseif ($imagen !== '') {
        $editarUsuario = $conexion->prepare("UPDATE Usuarios SET nombre_usuario = ?, direccion = ?, perfil = ? WHERE dni = ?");
        $editarUsuario->bind_param("ssss", $nombreUsuario, $direccion, $imagen, $dni);
    } else {
        $editarUsuario = $conexion->prepare("UPDATE Usuarios SET nombre_usuario = ?, direccion = ? WHERE dni = ?");
        $editarUsuario->bind_param("sss", $nombreUsuario, $direccion, $dni);
    }

    $editarUsuario->execute();
    $filasAfectadas = $editarUsuario->affected_rows;
    $exito = $filasAfectadas > 0;
    $editarUsuario->close();
    return $exito;
}

// Verifico si existe la variable del botón que recibo desde el formulario
if (filter_has_var(INPUT_POST, 'enviarEditarUsuario')) {
    // Cojo el dni del usuario de la sesión
    $dniUsuarioSeleccionado = $_SESSION['identificadorUsuario'];
    // Valido los campos para evitar inyecciones XSS o html
    $nombreUsuario = validarUsuario(filter_input(INPUT_POST, 'nombreUsuario'));
    $direccion = validarDireccion(filter_input(INPUT_POST, 'direccion'));
    $contrasenaActual = validarContrasena(filter_input(INPUT_POST, 'contrasena_antigua'));
    $contrasenaNueva = validarContrasena(filter_input(INPUT_POST, 'contrasena_nueva'));
    // Me creo una variable para la coneión con la base de datos
    $conexionBaseDatos = Conexion::conexionBD();

    $nuevaImagen = '';
    //Aqui compruebo si se ha enviado el archivo imagen y no ha habido ningún error en la subida
    if (isset($_FILES['perfil']) && $_FILES['perfil']['error'] === UPLOAD_ERR_OK) {
        // Se obtiene el nombre del archivo sin los directorios
        $nombreImagen = basename($_FILES['perfil']['name']);
        // Aquí especifico la ruta donde se va a guardar el archivo
        $rutaDestino = 'imagenes/fotosPerfil/' . $nombreImagen;
        // Aquí especifico la ruta donde actualmente se encuentra el sistema de archivos
        $rutaServidor = '../' . $rutaDestino;
        // Aqui me creo un array con las extensiones que voy a permitir
        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        // Aquí convierto la extensión del archivo a minúsculas si el usuario ha introducido la extensión con mayúsculas
        $extensionArchivo = strtolower(pathinfo($nombreImagen, PATHINFO_EXTENSION));
        /* Si la extensión del archivo no coincide con las extensiones permitidas del array redirijo 
        al usuario a cuenta.php y le muestro un mensaje de error */
        if (!in_array($extensionArchivo, $extensionesPermitidas)) {
            $_SESSION['error'] = "Solo se permiten imágenes JPG, JPEG o PNG.";
            header('Location: cuenta.php');
            exit();
        }
        /* Aquí intenta mover el archivo desde la ruta donde el usuario ha seleccionado el archivo hasta la ruta donde se guardarán las
        imágenes en el sistema de archivos, si esto falla lo redirijo a cuenta.php y le muestro un mensaje de error */
        if (!move_uploaded_file($_FILES['perfil']['tmp_name'], $rutaServidor)) {
            $_SESSION['error'] = "Error al subir la nueva imagen.";
            header('Location: cuenta.php');
            exit();
        }
        $nuevaImagen = $rutaDestino;
    }
    // Si el nombre de usuario o la dirección están mal le muestro un mmensaje de error
    if (!$nombreUsuario || !$direccion) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        // Si el udsuario ya existe, le muestro un mensaje de error
    } elseif (usuarioYaExiste($conexionBaseDatos, $nombreUsuario, $dniUsuarioSeleccionado)) {
        $_SESSION['error'] = "El nombre de usuario ya está en uso. Por favor, elige otro nombre.";
    } else {
        // Si el usuario no existe procedo a actualizar sus campos
        $claveCifrada = '';
        // Si el usuario me ha introducido las dos contraseñas continúo
        if ($contrasenaActual && $contrasenaNueva) {
            // Aquí me guardo la contraseña actual cifrada de la base de datos
            $claveActualBD = obtenerContrasenaCifrada($conexionBaseDatos, $dniUsuarioSeleccionado);
            // Si la clave está mal o no coincide con la de la bade de datos muestro un mensaje de error
            if (!$claveActualBD || !password_verify($contrasenaActual, $claveActualBD)) {
                $_SESSION['error'] = "La contraseña actual es incorrecta.";
                header('Location: cuenta.php');
                exit();
            }
            // Si todo va bien cifro la nueva contraseña en la base de datos
            $claveCifrada = password_hash($contrasenaNueva, PASSWORD_DEFAULT);
        }
        /* Aquí actualizo sus campos y dependiendo de si me ha introducido sus campos o no, se 
        actualizarán esos campos */
        $exito = actualizarUsuario($conexionBaseDatos,
        $dniUsuarioSeleccionado,
        $nombreUsuario,
        $direccion,
        $claveCifrada ?? '',
        $nuevaImagen !== '' ? $nuevaImagen : ''  
        );


        if ($exito) {
            if ($nuevaImagen !== '') {
                /* Si se ha producido la actualización y la imagen no está vacia, quiere decir que todo
                ha ido bien y guardamos la nueva imagen en la sesión del usuario */
                $_SESSION['perfil'] = $nuevaImagen;
            }
            // Aquí le muestro un mensaje de éxito
            $_SESSION['mensaje'] = "Se han guardado correctamente los cambios.";
        } else {
            // Si no ha hecho ninguún cambio le mando un mensaje de que no ha cambiado nada
            $_SESSION['error'] = "No se han realizado cambios.";
        }
    }
    // Cierro conexión con la base de datos y redirijo a cuenta.php
    Conexion::cerrarConexionBD();
    header('Location: cuenta.php');
    exit();
}
?>

