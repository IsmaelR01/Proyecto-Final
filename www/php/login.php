<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilologinregistro.css">
</head>
<body>
    <a href="../index.php" class="volver-menu-principal">
        ← Volver al Inicio
    </a>

    <div class="card">
        <div class="card-header">
            <h3>Iniciar Sesión</h3>
        </div>
        <div class="card-body">
            <form action="controladorLogin.php" method="post">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Introduce el usuario</label><br>
                    <input type="text" class="form-control" id="usuario" name="usuario">
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Introduce la contraseña</label><br>
                    <input type="password" class="form-control" id="contrasena" name="contrasena">
                </div><br>

                <button type="submit" id="enviar" name="sesion" class="btn btn-primary">Iniciar Sesión</button><br>

                <?php if (isset($_SESSION['mensaje_error'])) { ?>
                    <div id="resultado" style="color: red; margin-bottom: 10px;">
                        <?php
                        echo $_SESSION['mensaje_error'];
                        unset($_SESSION['mensaje_error']);
                        ?>
                    </div>
                <?php }else { ?>
                    <div id="resultado" style="color: red; margin-bottom: 10px;"></div>
                <?php } ?>

            </form>
        </div>
        <div class="card-footer">
            <p>¿No tienes una cuenta? <a href="registro.php">Regístrate</a></p>
        </div>
    </div>

    <script src="../JavaScript/validacionFormularioSesion.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
