<?php
session_start();
require_once 'Conexion.php';
include_once 'funciones_validar.php';
// Creo una variable para la conexión con la base de datos
$conexionBaseDatos = Conexion::conexionBD();
// Si no existe la sesión del usuario o el rol no es admin, redirijo a la página principal
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
// Hago una consulta a la base de datos para obtener todos los usuarios excepto el administrador principal
$nombreUsuario = 'Admin01';
$consultaUsuarios = $conexionBaseDatos->prepare("SELECT u.dni,u.nombre_usuario, u.clave,u.direccion,u.email,u.id_rol, r.tipo FROM Usuarios u INNER JOIN Roles r ON u.id_rol = r.id_rol WHERE u.nombre_usuario != ? ORDER BY u.id_rol ASC");
$consultaUsuarios->bind_param("s", $nombreUsuario);
$consultaUsuarios->execute();

$resultado = $consultaUsuarios->get_result();
// Me creo un array vacío
$resultadoUsuarios = [];
// Recorro todas las filas
while ($fila = $resultado->fetch_assoc()) {
    $resultadoUsuarios[] = $fila;
}
// Intento obtener si el usuario envio una acción por el formulario
if (filter_has_var(INPUT_POST, 'accion')) {
    $accionSeleccionada = validarCadena(filter_input(INPUT_POST, 'accion'));
    /* Si ya hay una acción seleccionada, la obtengo a través de la sesión que la guardo en el script de editar y eliminar usuario 
    para que cuando vuelva a enviar el formulario, los desplegables no vuelvan a su estado original */
} else if (isset($_SESSION['accion'])) {
    $accionSeleccionada = $_SESSION['accion'];
}
// Me guardo las condiciones de las acciones en variables
$esAnadir = isset($accionSeleccionada) && $accionSeleccionada === 'añadir';
$esEditar = isset($accionSeleccionada) && $accionSeleccionada === 'editar';
$esEliminar = isset($accionSeleccionada) && $accionSeleccionada === 'eliminar';
// Si la acción es añadir redirijo al usuario a registro.php donde el administrador podrá dar de alta a usuarios con privilegios de administrador
if ($esAnadir) {
    header('Location: registro.php');
    exit();
}
// Intento obtener si el usuario seleccionado del formulario
if (filter_has_var(INPUT_POST, 'seleccionUsuario')) {
    $usuarioSeleccionadoId = filter_input(INPUT_POST, 'seleccionUsuario');
} else if (isset($_SESSION['seleccionUsuario'])) {
    /* Si el usuario ya ha sido seleccionado lo obtengo a través de la sesión que la guardo en el script de editar y eliminar usuario 
    para que cuando vuelva a enviar el formulario, los desplegables no vuelvan a su estado original */
    $usuarioSeleccionadoId = $_SESSION['seleccionUsuario'];
}

// Borro los datos de las variables de sesión
unset($_SESSION['accion'], $_SESSION['seleccionUsuario']);
// Si existe un usuario seleccionado, lo busca en la lista de usuuarios que me viene de la base de datos
if (isset($usuarioSeleccionadoId)) {
    foreach ($resultadoUsuarios as $usuario) {
        // Si el usuario de la base de datos es igual al usuario seleccionado, me lo guardo en una nueva variable
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
    <!-- Enlace de volver al inicio -->
    <a href="../index.php" style="
        position: absolute;
        top: 20px;
        left: 150px;
        text-decoration: none;
        color: white;
        background-color: #002FFF; /* color sólido */
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: bold;
        z-index: 1000;
    ">
        ← Volver al Inicio
    </a>
    <div class="container d-flex flex-column align-items-center mt-4">
        <!-- Muestro mensajes de éxito o error a través de las sesiones -->
        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alert alert-success alert-dismissible fade show text-center w-75" role="alert">
                <?php echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } else if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger alert-dismissible fade show text-center w-75" role="alert">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
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
                            <option value="eliminar" <?php if ($esEliminar) echo 'selected' ?>>Eliminar Usuario</option>
                        </select>
                    </div>
                </form>
                <!-- Formulario para editar usuario -->
                <!-- Si la acción es editar selecciono un usuario del desplegable y le indico con un campo oculto que la acción es editar -->
                <?php if ($esEditar) { ?>
                    <form method="POST" action="administrarUsuarios.php" class="mb-3 text-center" id="formularioUsuario">
                        <div class="mb-3 text-center">
                            <input type="hidden" name="accion" value="editar">
                            <label for="seleccionUsuario" class="form-label">Selecciona un usuario:</label>
                            <select class="form-select text-center form-select-sm mx-auto" style="width: 60%;" name="seleccionUsuario" id="seleccionUsuario">
                                <option value="">-- Selecciona --</option>
                                <?php foreach ($resultadoUsuarios as $usuario) { ?>
                                    <option value="<?php echo htmlspecialchars($usuario['dni']) ?>" <?php if (isset($usuarioSeleccionadoId) && $usuarioSeleccionadoId == $usuario['dni']) echo 'selected' ?>>
                                        <?php echo htmlspecialchars($usuario['nombre_usuario']) . " (". htmlspecialchars($usuario['tipo']) .")";?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                    </form>
                    <!-- Si existe el usuario seleccionado que me guardo del bucle foreach entonces lo mando por un campo oculto al script 
                     editarUsuario.php -->
                    <?php if (isset($usuarioSeleccionado)) { ?>
                        <form method="POST" action="editarUsuario.php" id="formularioEditar">
                            <input type="hidden" name="seleccionUsuario" value="<?php echo htmlspecialchars($usuarioSeleccionado['dni']) ?>">
                            <div class="mb-3 text-center">
                                <div class="mb-3">
                                    <label class="form-label">Nombre de usuario:</label>
                                    <input type="text" id="editarNombreUsuario" class="form-control text-center" name="nombre" value="<?php echo htmlspecialchars($usuarioSeleccionado['nombre_usuario']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <input type="email" id="editarEmailUsuario" class="form-control text-center" name="email" value="<?php echo htmlspecialchars($usuarioSeleccionado['email']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dirección:</label>
                                    <input type="text" id="editarDireccionUsuario" class="form-control text-center" name="direccion" value="<?php echo htmlspecialchars($usuarioSeleccionado['direccion']) ?>">
                                </div>
                                <div class="d-grid w-50 mx-auto">
                                    <button type="submit" id="botonEditarEnviar" name="botonEditarEnviar" class="btn btn-success btn-sm">Guardar Cambios</button>
                                </div>
                            </div><br>
                            <div id="resultadoEditarUsuario" style="color: red; margin-bottom: 10px; text-align: center;">

                            </div>
                        </form>
                    <?php } ?>
                <?php } ?>

                <!-- Formulario para eliminar usuario -->
                <!-- Si la acción es eliminar, me guardo el usuario seleccionado en campo oculto y lo mando a eliminarUsuario.php -->
                <?php if ($esEliminar) { ?>
                    <form method="POST" action="eliminarUsuario.php" class="text-center" id="formularioEliminar">
                        <div class="mb-3 text-center">
                            <input type="hidden" name="seleccionUsuario" value="<?php echo htmlspecialchars($usuarioSeleccionado['dni']) ?>">
                            <label for="usuario_id" class="form-label">Selecciona un usuario para eliminar:</label>
                            <select class="form-select form-select-sm mx-auto" style="width: 60%;" name="seleccionUsuario" id="seleccionUsuario">
                                <option value="">-- Selecciona --</option>
                                <?php foreach ($resultadoUsuarios as $usuario) { ?>
                                    <option value="<?php echo htmlspecialchars($usuario['dni']) ?>">
                                        <?php echo htmlspecialchars($usuario['nombre_usuario']) . " (". htmlspecialchars($usuario['tipo']) .")"; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>
                        <div class="d-grid w-50 mx-auto">
                            <button type="submit" name="botonEliminarEnviar" class="btn btn-danger btn-sm">Eliminar Usuario</button>
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
        // Esto hace que el código se ejecute cuando el html esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            /* Esto hace que cuando el usuario seleccione una acción, se envíe automáticamente el formulario y recargue la página con la
            nueva acción seleccionada */
            const accionSelect = document.getElementById('accion');
            const formularioAccion = document.getElementById('formularioAccion');

            accionSelect.addEventListener('change', function() {
                formularioAccion.submit();
            });
            /* Esto hace que cuando el usuario seleccione un usuario del desplegable, se envíe automáticamente el formulario y recargue la
            página o con el formulario con los campos para editar o directamente con el botón de eliminar si la acción es eliminar */
            const seleccionUsuario = document.getElementById('seleccionUsuario');
            const formularioUsuario = document.getElementById('formularioUsuario');

            if (seleccionUsuario && formularioUsuario) {
                seleccionUsuario.addEventListener('change', function() {
                    formularioUsuario.submit();
                });
            }
            /* Esto lo que hace es validar que se haya seleccionado un usuario del desplegable en el caso de que la acción sea eliminar 
            y si no se ha seleccionado ningún usuario previene el envio del formulario y muestra un mensaje de error al usuario */
            const formularioEliminar = document.getElementById('formularioEliminar');

            const mensajeEliminar = document.getElementById('mensajeEliminar');

            if (formularioEliminar && mensajeEliminar) {
                formularioEliminar.addEventListener('submit', function(e) {
                    if (seleccionUsuario && seleccionUsuario.value === "") {
                        e.preventDefault();
                        mensajeEliminar.innerHTML = "Debes seleccionar un usuario válido para eliminar.";
                    }
                });
            }
        });
    </script>
    <!-- Archivo js para la validación del formulario de editar usuario -->
    <script src="../JavaScript/validacionFormularioEditarUsuarioInterfazAdministración.js"></script>

</body>

</html>