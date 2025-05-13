<?php
session_start();
if(!isset($_SESSION['identificadorUsuario'])) {
    header('Location: ../index.php');
    exit();
}
require_once 'Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
$identificadorUsuario = $_SESSION['identificadorUsuario'];
$consultaUsuario = $conexionBaseDatos->prepare("SELECT * FROM Usuarios WHERE dni = ?");
$consultaUsuario->bind_param("s", $identificadorUsuario);
$consultaUsuario->execute();
$resultado = $consultaUsuario->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .breadcrumb {
            font-size: 1.75rem; 
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Cuenta</li>
            </ol>
        </nav>
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
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-header text-center bg-success text-white">
                        <h4>Editar Cuenta</h4>
                    </div>
                    <div class="card-body">
                        <?php while ($resultadoUsuario = $resultado->fetch_assoc()) { ?>
                            <form method="POST" action="editarCuenta.php" id="formularioEditar" enctype="multipart/form-data">
                                <input type="hidden" name="usuarioLogueado" value="<?php echo htmlspecialchars($resultadoUsuario['dni']) ?>">

                                <div class="mb-3 text-center">
                                    <label class="form-label">Foto de perfil:</label><br>
                                    <img src="../<?php echo htmlspecialchars($_SESSION['perfil'] ?? 'imagenes/fotosPerfil/emoticono.jpg'); ?>" alt="Foto de perfil" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                                    <input type="file" class="form-control form-control-sm mt-2" name="perfil" accept="image/jpeg, image/png, image/jpg">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nombre de usuario:</label>
                                    <input type="text" class="form-control form-control-sm" name="nombre" value="<?php echo htmlspecialchars($resultadoUsuario['nombre_usuario']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Introduce la contraseña actual</label>
                                    <input type="password" class="form-control form-control-sm" name="contrasena_antigua">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Introduce la contraseña nueva</label>
                                    <input type="password" class="form-control form-control-sm" name="contrasena_nueva">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <input type="email" class="form-control form-control-sm" name="email" value="<?php echo htmlspecialchars($resultadoUsuario['email']) ?>" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dirección:</label>
                                    <input type="text" class="form-control form-control-sm" name="direccion" value="<?php echo htmlspecialchars($resultadoUsuario['direccion']) ?>">
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-sm">Guardar Cambios</button>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (opcional para funcionalidades interactivas) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>