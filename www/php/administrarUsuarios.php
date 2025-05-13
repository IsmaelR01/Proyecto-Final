<?php
session_start();
require_once 'Conexion.php';
include_once 'funciones_validar.php';
$conexionBaseDatos = Conexion::conexionBD();

if(isset($_SESSION['usuario']) && $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

$idRol = 1;
$consultaUsuarios = $conexionBaseDatos->prepare("SELECT * FROM Usuarios WHERE id_rol != ?");
$consultaUsuarios->bind_param("i", $idRol);
$consultaUsuarios->execute();

$resultado = $consultaUsuarios->get_result();
$resultadoUsuarios = $resultado->fetch_all(MYSQLI_ASSOC);

if (filter_has_var(INPUT_POST, 'accion')) {
    $accionSeleccionada = filter_input(INPUT_POST, 'accion');
} elseif (isset($_SESSION['accion'])) {
    $accionSeleccionada = $_SESSION['accion'];
}
$esAnadir = isset($accionSeleccionada) && $accionSeleccionada === 'añadir';
$esEditar = isset($accionSeleccionada) && $accionSeleccionada === 'editar';
$esEliminar = isset($accionSeleccionada) && $accionSeleccionada === 'eliminar';

if($esAnadir) {
    header('Location: registro.php');
    exit();
} 
if (filter_has_var(INPUT_POST, 'seleccionUsuario')) {
    $usuarioSeleccionadoId = filter_input(INPUT_POST, 'seleccionUsuario');
} elseif (isset($_SESSION['seleccionUsuario'])) {
    $usuarioSeleccionadoId = $_SESSION['seleccionUsuario'];
}


unset($_SESSION['accion'], $_SESSION['seleccionUsuario']);

if(isset($usuarioSeleccionadoId)) {
    foreach ($resultadoUsuarios as $usuario) {
        if ($usuario['dni'] == $usuarioSeleccionadoId) {
            $usuarioSeleccionado = $usuario;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estiloInterfazAdministracion.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex flex-column align-items-center mt-4">
    <?php if (isset($_SESSION['mensaje'])) { ?>
        <div class="alert alert-success alert-dismissible fade show text-center w-75" role="alert">
            <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } else if(isset($_SESSION['mensaje_error'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show text-center w-75" role="alert">
            <?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>
    <div class="card card-custom shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Administrar Usuarios</h4>
        </div>
        <div class="card-body">
            <!-- Formulario para seleccionar acción -->
            <form method="POST" action="administrarUsuarios.php" class="mb-4" id="formularioAccion">
                <div class="mb-3 text-center">
                    <label for="accion" class="form-label">Selecciona una acción:</label>
                    <select class="form-select text-center form-select-sm mx-auto" style="width: 60%;" name="accion" id="accion">
                        <option value="">-- Elige una opción --</option>
                        <option value="añadir" <?php if ($esAnadir) echo 'selected' ?>>Añadir Usuario</option>
                        <option value="editar" <?php if ($esEditar) echo 'selected' ?>>Editar Usuario</option>
                        <option value="eliminar" <?php if($esEliminar) echo 'selected' ?>>Eliminar Usuario</option>
                    </select>
                </div>
            </form>
            <!-- Formulario para editar usuario -->
            <?php if ($esEditar) { ?>
                <form method="POST" action="administrarUsuarios.php" class="mb-3 text-center" id="formularioUsuario">
                    <div class="mb-3 text-center">
                        <input type="hidden" name="accion" value="editar">
                        <label for="seleccionUsuario" class="form-label">Selecciona un usuario:</label>
                        <select class="form-select text-center form-select-sm mx-auto" style="width: 60%;" name="seleccionUsuario" id="seleccionUsuario">
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($resultadoUsuarios as $usuario) { ?>
                                <option value="<?php echo htmlspecialchars($usuario['dni']) ?>" <?php if(isset($usuarioSeleccionadoId) && $usuarioSeleccionadoId == $usuario['dni']) echo 'selected' ?>>
                                    <?php echo htmlspecialchars($usuario['nombre_usuario']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    
                </form>

                <?php if (isset($usuarioSeleccionado)) { ?>
                    <form method="POST" action="editarUsuario.php" id="formularioEditar">
                        <input type="hidden" name="seleccionUsuario" value="<?php echo htmlspecialchars($usuarioSeleccionado['dni']) ?>">
                        <div class="mb-3 text-center">
                            <div class="mb-3">
                                <label class="form-label">Nombre de usuario:</label>
                                <input type="text" class="form-control text-center form-control-sm" name="nombre" value="<?php echo htmlspecialchars($usuarioSeleccionado['nombre_usuario']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" class="form-control text-center form-control-sm" name="email" value="<?php echo htmlspecialchars($usuarioSeleccionado['email']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección:</label>
                                <input type="text" class="form-control text-center form-control-sm" name="direccion" value="<?php echo htmlspecialchars($usuarioSeleccionado['direccion']) ?>">
                            </div>
                            <div class="d-grid w-50 mx-auto">
                                <button type="submit" class="btn btn-success btn-sm">Guardar Cambios</button>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            <?php } ?>

            <!-- Formulario para eliminar usuario -->
            <?php if ($esEliminar) { ?>
                <form method="POST" action="eliminarUsuario.php" class="text-center">
                    <div class="mb-3 text-center">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="seleccionUsuario" value="<?php echo htmlspecialchars($usuarioSeleccionado['dni']) ?>">
                        <label for="usuario_id" class="form-label">Selecciona un usuario para eliminar:</label>
                        <select class="form-select form-select-sm mx-auto" style="width: 60%;" name="seleccionUsuario" id="seleccionUsuario">
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($resultadoUsuarios as $usuario) { ?>
                                <option value="<?php echo htmlspecialchars($usuario['dni']) ?>">
                                    <?php echo htmlspecialchars($usuario['nombre_usuario']) ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>
                    <div class="d-grid w-50 mx-auto">
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar Usuario</button>
                    </div><br>
                    <div id="mensajeEliminar" style="color: red; margin-bottom: 10px;">

                    </div>
                </form>
            <?php } ?>
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
<script src="../JavaScript/validacionFormulariosAdministracionUsuarios.js" defer></script>

</body>
</html>
