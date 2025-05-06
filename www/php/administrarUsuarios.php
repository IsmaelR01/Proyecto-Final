<?php
session_start();
require_once 'Conexion.php';

$conexionBaseDatos = Conexion::conexionBD();

$idRol = 1;
$consultaUsuarios = $conexionBaseDatos->prepare("SELECT * FROM Usuarios WHERE id_rol != ?");
$consultaUsuarios->bind_param("i", $idRol);
$consultaUsuarios->execute();

$resultadoUsuarios = $consultaUsuarios->get_result()->fetch_all(MYSQLI_ASSOC);

$accionSeleccionada = $_POST['accion'] ?? '';
$usuarioSeleccionadoId = $_POST['seleccionUsuario'] ??  '';

$usuarioSeleccionado = null;

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url("../imagenes/interfazAdministracion.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            min-height: 100vh; 
            display: flex;
            justify-content: center;
            align-items: flex-start; 
            margin: 0;
            padding: 40px 0; 
            overflow-y: auto; 
        }
        .center-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-custom {
            width: 100%;
            max-width: 600px;
        }
        .form-select-sm {
            max-width: 50%;
        }
    </style>
</head>
<body>
<div class="container center-container">
    <div class="card card-custom shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Administrar Usuarios</h4>
        </div>
        <div class="card-body">

            <!-- Formulario para seleccionar acción -->
            <form method="POST" action="administrarUsuarios.php" class="mb-4" id="formularioAccion">
                <div class="mb-3 text-center">
                    <label for="accion" class="form-label">Selecciona una acción:</label>
                    <select class="form-select form-select-sm mx-auto" style="width: 60%;" name="accion" id="accion">
                        <option value="">-- Elige una opción --</option>
                        <option value="editar" <?= $accionSeleccionada === 'editar' ? 'selected' : '' ?>>Editar Usuario</option>
                        <option value="eliminar" <?= $accionSeleccionada === 'eliminar' ? 'selected' : '' ?>>Eliminar Usuario</option>
                    </select>
                </div>
            </form>

            <!-- Formulario para editar usuario -->
            <?php if ($accionSeleccionada === 'editar') { ?>
                <form method="POST" action="administrarUsuarios.php" class="mb-3 text-center" id="formularioUsuario">
                    <div class="mb-3 text-center">
                        <input type="hidden" name="accion" value="editar">
                        <label for="seleccionUsuario" class="form-label">Selecciona un usuario:</label>
                        <select class="form-select form-select-sm mx-auto" style="width: 60%;" name="seleccionUsuario" id="seleccionUsuario">
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($resultadoUsuarios as $usuario): ?>
                                <option value="<?= htmlspecialchars($usuario['dni']) ?>" <?= $usuarioSeleccionadoId == $usuario['dni'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($usuario['nombre_usuario']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                </form>

                <?php if ($usuarioSeleccionado) { ?>
                    <form method="POST" action="editarUsuario.php" id="formularioEditar">
                        <div class="mb-3 text-center">
                            <div class="mb-3">
                                <label class="form-label">Nombre de usuario:</label>
                                <input type="text" class="form-control form-control-sm" name="nombre" value="<?= htmlspecialchars($usuarioSeleccionado['nombre_usuario']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" class="form-control form-control-sm" name="email" value="<?= htmlspecialchars($usuarioSeleccionado['email']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección:</label>
                                <input type="text" class="form-control form-control-sm" name="direccion" value="<?= htmlspecialchars($usuarioSeleccionado['direccion']) ?>">
                            </div>
                            <div class="d-grid w-50 mx-auto">
                                <button type="submit" class="btn btn-success btn-sm">Guardar Cambios</button>
                            </div>
                            <?php if (isset($_SESSION['mensaje'])) { ?>
                                <div class="mt-2 text-success text-center">
                                    <?= $_SESSION['mensaje'];
                                    unset($_SESSION['mensaje']); ?>
                                </div>
                            <?php } else if(isset($_SESSION['error'])){ ?>
                                <div class="mt-2 text-danger text-center">
                                <?= $_SESSION['error'];
                                unset($_SESSION['error']); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </form>
                <?php } ?>
            <?php } ?>

            <!-- Formulario para eliminar usuario -->
            <?php if ($accionSeleccionada === 'eliminar'): ?>
                <form method="POST" action="eliminarUsuario.php" class="text-center">
                    <div class="mb-3 text-center">
                        <label for="usuario_id" class="form-label">Selecciona un usuario para eliminar:</label>
                        <select class="form-select form-select-sm mx-auto" style="width: 60%;" name="seleccionUsuario" id="seleccionUsuario">
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($resultadoUsuarios as $usuario): ?>
                                <option value="<?= htmlspecialchars($usuario['dni']) ?>">
                                    <?= htmlspecialchars($usuario['nombre_usuario']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="d-grid w-50 mx-auto">
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar Usuario</button>
                    </div>

                    <?php if (isset($_SESSION['mensaje'])) { ?>
                        <div class="mt-2 text-success text-center">
                            <?= $_SESSION['mensaje'];
                                unset($_SESSION['mensaje']); ?>
                        </div>
                    <?php } else if(isset($_SESSION['error'])){ ?>
                        <div class="mt-2 text-danger text-center">
                            <?= $_SESSION['error'];
                                unset($_SESSION['error']); ?>
                        </div>
                    <?php } ?>
                </form>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const accionSelect = document.getElementById('accion');
    const formularioAccion = document.getElementById('formularioAccion');

    accionSelect.addEventListener('change', function () {
        formularioAccion.submit();
    });

    const seleccionUsuario = document.getElementById('seleccionUsuario');
    const formularioUsuario = document.getElementById('formularioUsuario');

    if (seleccionUsuario && formularioUsuario) {
        seleccionUsuario.addEventListener('change', function () {
            formularioUsuario.submit();
        });
    }
});
</script>

</body>
</html>
