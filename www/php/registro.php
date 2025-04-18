<?php
// Aquí puedes incluir tu sesión si es necesario, como en el caso de login.php
// session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJv+M1umXk5DB7Pt9zDntM5q9B1/a9HhDlzkYngRHDL7k6X7mLntM4V8dm2w" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilologinregistro.css">
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h3>Registro de Usuario</h3>
        </div>
        <div class="card-body">
            <form action="controladorRegistro.php" method="post">
                <div class="mb-3">
                    <label for="dni" class="form-label">Introduce el DNI</label><br>
                    <input type="text" class="form-control" id="dni" name="dni" required>
                </div>

                <div class="mb-3">
                    <label for="usuario" class="form-label">Introduce el usuario</label><br>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Introduce el email</label><br>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Introduce la contraseña</label><br>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>

                <button type="submit" name="registrar" class="btn btn-primary">Registrar</button>
            </form>
        </div>
        <div class="card-footer">
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
        </div>
    </div>

    <!-- Enlazamos con el script de Bootstrap si es necesario -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0Xr7RUbiT8lylQJlM5H56hlXTp0lvoFpt+msPnP1Dth46+gq" crossorigin="anonymous"></script>
    
</body>
</html>
