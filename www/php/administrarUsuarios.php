<?php
session_start();
require_once 'Conexion.php';

$conexionBaseDatos = Conexion::conexionBD();

// Obtener usuarios con rol distinto de admin (id_rol = 1)
$idRol = 1;
$consultaUsuarios = $conexionBaseDatos->prepare("SELECT * FROM Usuarios WHERE id_rol != ?");
$consultaUsuarios->bind_param("i", $idRol);
$consultaUsuarios->execute();

// Guardar resultado en $resultadoUsuarios
$resultadoUsuarios = $consultaUsuarios->get_result()->fetch_all(MYSQLI_ASSOC);

$accionSeleccionada = $_POST['accion'] ?? '';
$usuarioSeleccionadoId = $_POST['usuario_id'] ?? '';
$usuarioSeleccionado = null;

// Buscar usuario seleccionado sin usar break
foreach ($resultadoUsuarios as $usuario) {
    if ($usuario['dni'] == $usuarioSeleccionadoId) {
        $usuarioSeleccionado = $usuario;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
</head>
<body>
    <h1>Administrar Usuarios</h1>

    <!-- Formulario para seleccionar acción -->
    <form method="POST" action="administrarUsuarios.php">
        <label for="accion">Selecciona una acción:</label>
        <select name="accion" id="accion" onchange="this.form.submit()">
            <option value="">-- Elige una opción --</option>
            <option value="editar" <?= $accionSeleccionada === 'editar' ? 'selected' : '' ?>>Editar Usuario</option>
            <option value="eliminar" <?= $accionSeleccionada === 'eliminar' ? 'selected' : '' ?>>Eliminar Usuario</option>
        </select>
    </form>

    <hr>

    <!-- Formulario para editar usuario -->
    <?php if ($accionSeleccionada === 'editar'): ?>
        <!-- Seleccionar usuario a editar -->
        <form method="POST" action="administrarUsuarios.php">
            <input type="hidden" name="accion" value="editar">

            <label for="usuario_id">Selecciona un usuario:</label>
            <select name="usuario_id" id="usuario_id" onchange="this.form.submit()">
                <option value="">-- Selecciona --</option>
                <?php foreach ($resultadoUsuarios as $usuario): ?>
                    <option value="<?= htmlspecialchars($usuario['dni']) ?>" <?= $usuarioSeleccionadoId == $usuario['dni'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($usuario['nombre_usuario']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Formulario de edición -->
        <?php if ($usuarioSeleccionado): ?>
            <form method="POST" action="editarUsuario.php">
                <input type="hidden" name="dni" value="<?= htmlspecialchars($usuarioSeleccionado['dni']) ?>">

                <label>Nombre de usuario:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuarioSeleccionado['nombre_usuario']) ?>"><br>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($usuarioSeleccionado['email']) ?>"><br>

                <label>Dirección:</label>
                <input type="text" name="direccion" value="<?= htmlspecialchars($usuarioSeleccionado['direccion']) ?>"><br>

                <button type="submit">Guardar Cambios</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Formulario para eliminar usuario -->
    <?php if ($accionSeleccionada === 'eliminar'): ?>
        <form method="POST" action="eliminarUsuario.php">
            <label for="usuario_id">Selecciona un usuario para eliminar:</label>
            <select name="usuario_id" id="usuario_id">
                <option value="">-- Selecciona --</option>
                <?php foreach ($resultadoUsuarios as $usuario): ?>
                    <option value="<?= htmlspecialchars($usuario['dni']) ?>">
                        <?= htmlspecialchars($usuario['nombre_usuario']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <button type="submit">Eliminar Usuario</button>
        </form>
    <?php endif; ?>
</body>
</html>

<?php if (isset($_SESSION['mensaje'])): ?>
    <p style="color: green;"><?= $_SESSION['mensaje'] ?></p>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?= $_SESSION['error'] ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

