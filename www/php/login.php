<?php 
session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <form action="controladorLogin.php" method="post">
        <label>Introduce el usuario: 
            <input type="text" name="usuario" required>
        </label><br><br>

        <label>Introduce la contraseña: 
            <input type="password" name="contrasena" required>
        </label><br><br>

        <button type="submit" name="sesion">Iniciar Sesión</button>
    </form>
    <p>¿No tienes una cuenta?<a href="registro.php">Regístrate</a></p>
</body>
</html>