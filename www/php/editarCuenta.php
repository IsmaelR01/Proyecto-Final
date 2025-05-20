<?php
session_start();
require_once 'Conexion.php';
include 'funciones_validar.php';

if (!isset($_SESSION['identificadorUsuario'])) {
    header('Location: ../index.php');
    exit();
}

if (filter_has_var(INPUT_POST, 'usuarioLogueado')) {

    $dniUsuarioSeleccionado = $_SESSION['identificadorUsuario'];
    $nombreUsuario = validarUsuario(filter_input(INPUT_POST, 'nombreUsuario'));
    $direccion = validarDireccion(filter_input(INPUT_POST, 'direccion'));
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

    if (!$nombreUsuario || !$direccion) {
        $_SESSION['error'] = "Alguno de los campos introducidos no son correctos.";
    } else {
        // Verifica si el nombre de usuario ya está en uso
        $consultaUsuarioExistente = $conexionBaseDatos->prepare("SELECT COUNT(*) FROM Usuarios WHERE nombre_usuario = ? AND dni != ?");
        $consultaUsuarioExistente->bind_param("ss", $nombreUsuario, $dniUsuarioSeleccionado);
        $consultaUsuarioExistente->execute();
        $resultadoUsuarioExistente = $consultaUsuarioExistente->get_result();
        $existeUsuario = $resultadoUsuarioExistente->fetch_row()[0];
        $consultaUsuarioExistente->close();

        if ($existeUsuario > 0) {
            $_SESSION['error'] = "El nombre de usuario ya está en uso. Por favor, elige otro nombre.";
        } else {
            // Cambianddo la contraseña, introduciendo la contraseña actual y la contraseña antigua
            if ($contrasenaActual && $contrasenaNueva) {
                $consultaContrasenaActual = $conexionBaseDatos->prepare("SELECT nombre_usuario, clave FROM Usuarios WHERE dni = ?");
                $consultaContrasenaActual->bind_param("s", $dniUsuarioSeleccionado);
                $consultaContrasenaActual->execute();
                $resultado = $consultaContrasenaActual->get_result();

                if ($resultado->num_rows > 0) {
                    $resultadoContrasenaActual = $resultado->fetch_assoc();
                    $contrasenaActualCifrada = $resultadoContrasenaActual['clave'];

                    if (password_verify($contrasenaActual, $contrasenaActualCifrada)) {
                        $contrasenaNuevaCifrada = password_hash($contrasenaNueva, PASSWORD_DEFAULT);

                        if ($nuevaImagen !== '') {
                            $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, clave = ?, direccion = ?, perfil = ? WHERE dni = ?");
                            $editarUsuario->bind_param("sssss", $nombreUsuario, $contrasenaNuevaCifrada, $direccion, $nuevaImagen, $dniUsuarioSeleccionado);
                        } else {
                            $editarUsuario = $conexionBaseDatos->prepare("UPDATE Usuarios SET nombre_usuario = ?, clave = ?, direccion = ? WHERE dni = ?");
                            $editarUsuario->bind_param("ssss", $nombreUsuario, $contrasenaNuevaCifrada, $direccion, $dniUsuarioSeleccionado);
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
                        $editarUsuario->close();
                    } else {
                        $_SESSION['error'] = "La contraseña actual es incorrecta.";
                    }
                } else {
                    $_SESSION['error'] = "No se pudo encontrar el usuario.";
                }
                $consultaContrasenaActual->close();
            } else {
                // Sin cambiar la contraseña, dejando los campos de contraseña vacíos
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
                $editarUsuario->close();
            }
        }
    }

    Conexion::cerrarConexionBD();

    header('Location: cuenta.php');
    exit();
}
?>
