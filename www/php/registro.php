<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
</head>
<body>
    <form action="controladorRegistro.php" method="post">
        <label>Introduce el dni: 
            <input type="text" name="dni" required>
        </label>
        <br><br>
        <label>Introduce el usuario: 
            <input type="text" name="usuario" required>
        </label>
        <br><br>
        <label>Introduce la contraseña: 
            <input type="password" name="contrasena" required>
        </label>
        <br><br>
        <button type="submit" name="registrar">Registrar</button>
    </form>
</body>
</html>