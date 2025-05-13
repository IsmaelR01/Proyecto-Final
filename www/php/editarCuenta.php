<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

if (!isset($_SESSION['identificadorUsuario'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dniUsuarioSeleccionado = $_SESSION['identificadorUsuario'];
    $nombreUsuario = validarUsuario(filter_input(INPUT_POST, 'nombre'));
    $direccion = validarDireccion(filter_input(INPUT_POST,'direccion'));
    $contrasenaActual = filter_input(INPUT_POST, 'contrasena_antigua');
    $contrasenaNueva = filter_input(INPUT_POST, 'contrasena_nueva');
    $conexionBaseDatos = Conexion::conexionBD();

    $nuevaImagen = '';
    if (isset($_FILES['perfil']) && $_FILES['perfil']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = basename($_FILES['perfil']['name']);
        $rutaDestino = 'imagenes/fotosPerfil/' . $nombreImagen;
        $rutaServidor = '../' . $rutaDestino;

        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        $extensionArchivo = strtolower(pathinfo($nombreImagen, PATHINFO_EXTENSION));

        if (!in_array($extensionArchivo, $extensionesPermitidas)) {
            $_SESSION['error'] = "Solo se permiten imágenes JPG, JPEG o PNG.";
            header('Location: cuenta.php');
            exit();
        }

        if (!move_uploaded_file($_FILES['perfil']['tmp_name'], $rutaServidor)) {
            $_SESSION['error'] = "Error al subir la nueva imagen.";
            header('Location: cuenta.php');
            exit();
        }
        $nuevaImagen = $rutaDestino;
    }

    // Validar campos que no son contraseñas
    if (!$nombreUsuario || !$direccion) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
        header('Location: cuenta.php');
        exit();
    } else {
        // Verificar si el nombre de usuario ya existe en la base de datos
        $consultaUsuarioExistente = $conexionBaseDatos->prepare("SELECT COUNT(*) FROM Usuarios WHERE nombre_usuario = ? AND dni != ?");
        $consultaUsuarioExistente->bind_param("ss", $nombreUsuario, $dniUsuarioSeleccionado);
        $consultaUsuarioExistente->execute();
        $resultadoUsuarioExistente = $consultaUsuarioExistente->get_result();
        $existeUsuario = $resultadoUsuarioExistente->fetch_row()[0];

        if ($existeUsuario > 0) {
            $_SESSION['error'] = "El nombre de usuario ya está en uso. Por favor, elige otro nombre.";
            header('Location: cuenta.php');
            exit();
        }

        // Solo verificar la contraseña si el campo de "contraseña actual" y "contraseña nueva" están presentes
        if ($contrasenaActual && $contrasenaNueva) {
            $consultaContrasenaActual = $conexionBaseDatos->prepare("SELECT nombre_usuario, clave FROM Usuarios WHERE dni = ?");
            $consultaContrasenaActual->bind_param("s", $dniUsuarioSeleccionado);
            $consultaContrasenaActual->execute();
            $resultado = $consultaContrasenaActual->get_result();

            if($resultado->num_rows > 0) {
                $resultadoContrasenaActual = $resultado->fetch_assoc();
                $contrasenaActualCifrada = $resultadoContrasenaActual['clave'];

                // Verificar si la contraseña actual proporcionada es correcta
                if (password_verify($contrasenaActual, $contrasenaActualCifrada)) {
                    // Si se proporciona una nueva contraseña, cifrarla
                    $contrasenaNuevaCifrada = password_hash($contrasenaNueva, PASSWORD_DEFAULT);
                    
                    // Actualizar usuario, incluyendo la nueva contraseña si fue proporcionada
                    if ($nuevaImagen !== '') {
                        $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, clave = ?, direccion = ?, perfil = ? WHERE dni = ?");
                        $editarUsuario->bind_param("sssss", $nombreUsuario, $contrasenaNuevaCifrada, $direccion, $nuevaImagen, $dniUsuarioSeleccionado);
                    } else {
                        $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, clave = ?, direccion = ? WHERE dni = ?");
                        $editarUsuario->bind_param("ssss", $nombreUsuario, $contrasenaNuevaCifrada, $direccion, $dniUsuarioSeleccionado);
                    }
                    
                    // Si la consulta se ejecuta correctamente, actualizar sesión y dar mensaje de éxito
                    if ($editarUsuario->execute()) {
                        if ($editarUsuario->affected_rows > 0) {
                            if ($nuevaImagen !== '') {
                                $_SESSION['perfil'] = $nuevaImagen;
                            }
                            $_SESSION['mensaje'] = "Se han guardado correctamente los cambios.";
                        } else {
                            $_SESSION['error'] = "No se han realizado cambios.";
                        }
                    } else {
                        $_SESSION['error'] = "Error al guardar los cambios.";
                    }
                } else {
                    $_SESSION['error'] = "La contraseña actual es incorrecta.";
                }
            } else {
                $_SESSION['error'] = "No se pudo encontrar el usuario.";
            }
        } else {
            // Si no se está cambiando la contraseña, solo actualizar nombre y dirección
            if ($nuevaImagen !== '') {
                $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, direccion = ?, perfil = ? WHERE dni = ?");
                $editarUsuario->bind_param("ssss", $nombreUsuario, $direccion, $nuevaImagen, $dniUsuarioSeleccionado);
            } else {
                $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, direccion = ? WHERE dni = ?");
                $editarUsuario->bind_param("sss", $nombreUsuario, $direccion, $dniUsuarioSeleccionado);
            }

            if ($editarUsuario->execute()) {
                if ($editarUsuario->affected_rows > 0) {
                    if ($nuevaImagen !== '') {
                        $_SESSION['perfil'] = $nuevaImagen;
                    }
                    $_SESSION['mensaje'] = "Se han guardado correctamente los cambios.";
                } else {
                    $_SESSION['error'] = "No se han realizado cambios.";
                }
            } else {
                $_SESSION['error'] = "Error al guardar los cambios.";
            }
        }
    }
    
    header('Location: cuenta.php');
    exit();
}
?>
