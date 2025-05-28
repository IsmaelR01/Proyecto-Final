<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilologinregistro.css">
</head>
<body>
    <!-- Botón de volver al menú principal -->
    <a href="../index.php" class="volver-menu-principal">
        ← Volver al Inicio
    </a>
    <div class="card">
        <div class="card-header">
            <h3>Registro de Usuario</h3>
        </div>
        <div class="card-body">
            <form action="controladorRegistro.php" method="post">
                <div class="mb-3">
                    <label for="dni" class="form-label">Introduce el DNI</label><br>
                    <input type="text" class="form-control" id="dni" name="dni">
                </div>

                <div class="mb-3">
                    <label for="usuario" class="form-label">Introduce el usuario</label><br>
                    <input type="text" class="form-control" id="usuario" name="usuario">
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label><br>
                    <input type="text" class="form-control" id="direccion" name="direccion">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Introduce el email</label><br>
                    <input type="text" class="form-control" id="email" name="email">
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Introduce la contraseña</label><br>
                    <input type="password" class="form-control" id="contrasena" name="contrasena">
                </div>

                <button type="submit" id="enviar" name="registrar" class="btn btn-primary">Registrar</button><br>
                <!-- Muestro los mensajes de éxito o error del lado del servidor y también muestro los errores del lado del cliente -->
                <div id="resultado" style="color: red; margin-bottom: 10px;">
                    <?php
                    if (isset($_SESSION['mensaje_error'])) {
                        echo $_SESSION['mensaje_error'];
                        unset($_SESSION['mensaje_error']);
                    } elseif (isset($_SESSION['mensajeExito'])) { ?>
                        <span style="color:green;"> <?php echo $_SESSION['mensajeExito'] ?></span>
                        <?php unset($_SESSION['mensajeExito']);
                    }
                    ?>
                </div>
            </form>
        </div>
        <!-- Si no existe la sesión del usuario o el rol es distinto al de admin, se mostrará este mensaje -->
        <?php if(!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') { ?>
            <div class="card-footer">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
            </div>
        <?php } ?>
        
    </div>
    <!-- Archivos js de Bootstrap y de la validación del formulario -->
    <script src="../JavaScript/validacionFormularioRegistro.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
