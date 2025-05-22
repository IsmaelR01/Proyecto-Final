<?php
session_start();
if (!isset($_SESSION['identificadorUsuario'])) {
    header('Location: ../index.php');
    exit();
}

require_once 'Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();

$identificadorUsuario = $_SESSION['identificadorUsuario'];
$consultaUsuario = $conexionBaseDatos->prepare("SELECT * FROM Usuarios WHERE dni = ?");
$consultaUsuario->bind_param("s", $identificadorUsuario);

if ($consultaUsuario->execute()) {
    $resultado = $consultaUsuario->get_result();

    if ($resultado->num_rows === 0) {
        $_SESSION['error'] = "No se encontró la información del usuario.";
        $consultaUsuario->close();
        Conexion::cerrarConexionBD();
        header('Location: ../index.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Error al obtener los datos del usuario.";
    $consultaUsuario->close();
    Conexion::cerrarConexionBD();
    header('Location: ../index.php');
    exit();
}

// Cerramos la conexión después de obtener los datos
$consultaUsuario->close();
Conexion::cerrarConexionBD();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estiloEditarCuenta.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- Enlace de volver al inicio -->
    <a href="../index.php" class="volver-menu-principal">
        ← Volver al Inicio
     </a>
    <div class="container py-5">
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow">
                    <div class="card-header text-center bg-success text-white">
                        <h4>Editar Cuenta</h4>
                    </div>
                    <div class="card-body">
                        <?php while ($resultadoUsuario = $resultado->fetch_assoc()) { ?>
                            <form method="POST" action="editarCuenta.php" id="formularioEditarCuenta" enctype="multipart/form-data">
                                <input type="hidden" name="usuarioLogueado" value="<?php echo htmlspecialchars($resultadoUsuario['dni']) ?>">

                                <div class="mb-3 text-center">
                                    <label class="form-label">Foto de perfil:</label><br>
                                    <img src="../<?php echo htmlspecialchars($_SESSION['perfil'] ?? 'imagenes/fotosPerfil/emoticono.jpg'); ?>" alt="Foto de perfil" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                                    <input type="file" class="form-control mt-2" id= "editarPerfil" name="perfil" accept="image/jpeg, image/png, image/jpg">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nombre de usuario:</label>
                                    <input type="text" class="form-control" id="editarNombreUsuario" name="nombreUsuario" value="<?php echo htmlspecialchars($resultadoUsuario['nombre_usuario']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Introduce la contraseña actual</label>
                                    <input type="password" class="form-control" id="contrasena_antigua" name="contrasena_antigua">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Introduce la contraseña nueva</label>
                                    <input type="password" class="form-control" id="contrasena_nueva" name="contrasena_nueva">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($resultadoUsuario['email']) ?>" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dirección:</label>
                                    <input type="text" class="form-control" id="editarDireccion" name="direccion" value="<?php echo htmlspecialchars($resultadoUsuario['direccion']) ?>">
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                </div><br>

                                <div id="resultadoEditarCuenta" style="color: red; margin-bottom: 10px; text-align: center;">

                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JavaScript/validacionFormularioEditarCuenta.js"></script>
</body>

</html>