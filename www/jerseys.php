<?php
date_default_timezone_set('Europe/Madrid');
session_start();
require_once 'php/Conexion.php';
// Creo una variable para hacer conexión con la base de datos
$conexionBaseDatos = Conexion::conexionBD();
// Le meto la búsqueda en la variable para que me busque los jerseys
$busqueda = 'J%';
// Hago la consulta para sacar todos los datos del producto y reemplazo el . por la , para los decimales del precio
$consultaProducto = $conexionBaseDatos->prepare("SELECT cod_producto, nombre, modelo, descripcion, imagen, REPLACE(FORMAT(precio, 2), '.', ',') AS precio, CIF FROM Productos WHERE cod_producto like ?");
$consultaProducto->bind_param("s", $busqueda);
$consultaProducto->execute();
$resultado = $consultaProducto->get_result();
// Hago una consulta para sacar el CIF y el nombre del proveedor para el desplegable a la hora de añadir el proveedor 
$consultaProveedores = $conexionBaseDatos->query("SELECT CIF, nombre_proveedor FROM Proveedores");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Jerseys</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estiloJerseysChaquetas.css">
</head>

<body>
    <?php include 'php/navbar.php'; ?>

    <main class="container my-5">
        <h2 class="section-title">Productos</h2>
        <!-- Meto un breadcrumb para que el usuario pueda volver al inicio -->
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jerseys</li>
            </ol>
        </nav>
        <!-- Muestro los mensajes de error o exito que vienen del servidor -->
        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } else if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <!-- INICIO Botón Añadir producto -->
        <!-- Si existe el usuario y tiene el rol administrador le aparecerá el boton de añadir producto -->
        <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
            <div class="d-flex gap-1 my-3">
                <!-- Botón para abrir modal de añadir producto y en el data-bs-target paso el identificaodr del
                modal añadir para que cuando el usuario pulse el botón de añadir sepa identificar el modal sin problemas -->
                <button type="button" class="btn btn-success btn-crud" data-bs-toggle="modal" data-bs-target="#añadirModal">
                    Añadir producto
                </button>
            </div>
            <!-- FIN Botón Añadir producto -->

            <!-- Modal Añadir Producto -->
            <div class="modal fade" id="añadirModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="php/añadirProducto.php" id="formularioAñadirProducto" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="origen" value="jerseys">
                            <div class="modal-header">
                                <h5 class="modal-title">Añadir Nuevo Producto</h5>
                                <button type="button" id="botonAñadirCerrar" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Código</label>
                                    <input type="text" class="form-control" id="añadirCod_producto" name="cod_producto">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="añadirNombre" name="nombre">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="añadirModelo" name="modelo">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" id="añadirDescripcion" name="descripcion"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Precio (sin € y los decimales con .)</label>
                                    <input type="text" class="form-control" id="añadirPrecio" name="precio">
                                </div>
                                <div class="mb-3">
                                    <label for="cif" class="form-label">Proveedor</label>
                                    <select name="cif" id="cif" class="form-select">
                                        <option value="" selected disabled>Selecciona un proveedor</option>
                                        <!-- Recorro las filas y lo devuelvo en forma de array el nombre de los proveedores -->
                                        <?php while ($proveedor = $consultaProveedores->fetch_assoc()) { ?>
                                            <option value="<?php echo htmlspecialchars($proveedor['CIF']); ?>">
                                                <?php echo htmlspecialchars($proveedor['nombre_proveedor']) . " (" . htmlspecialchars($proveedor['CIF']) . ")"; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Seleccionar imagen</label>
                                    <input type="file" class="form-control" id="añadirImagen" name="imagen" accept="image/jpeg, image/png, image/jpg">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="botonAñadirEnviar" class="btn btn-success">Añadir Producto</button>
                                <button type="button" id="botonAñadirCancelar" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div><br>
                            <!-- Muestro los errores que vengan de la validación de JS -->
                            <div id="resultadoProducto" style="color: red; margin-bottom: 10px; text-align: center;">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="d-flex flex-wrap gap-4 justify-content-start my-4">
            <!-- Recorro las filas y le asigno un identificador a los modales para identificarlos -->
            <?php while ($producto = $resultado->fetch_assoc()) {
                $identificadorModal = "modal_" . htmlspecialchars($producto['cod_producto']);
            ?>
                <!-- Aquí empieza la card y se muestra la información básica, 
                solo se mostrará el botón más información si se ha iniciado sesión tanto para los clientes como los administradores -->
                <div class="card" style="width: 18rem;">
                    <img src="<?php echo htmlspecialchars($producto['imagen']) ?>" class="card-img-top" alt="Imagen del producto">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['cod_producto']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                        <p class="card-text"><?php echo htmlspecialchars($producto['precio']) . " €"; ?></p>
                        <!-- Si existe la sesión del usuario y el rol es usuario o administrador le aparecerá el botón más información -->
                        <!-- INICIO Botón Más Información -->
                        <?php if (isset($_SESSION['usuario']) && (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'cliente' || $_SESSION['rol'] === 'admin'))) { ?>
                            <!-- El data-bs-target hace referencia al id del modal más información, para que pueda identificarlo
                             de forma única sin problemas -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $identificadorModal; ?>">
                                Más información
                            </button><br><br>
                        <?php }
                        // FIN BOTÓN MÁS INFORMACIÓN
                        // INICIO BOTÓN COMPRAR
                        // Si existe el usuario y el rol es cliente le aparecerá el botón de comprar en cada producto
                        if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'cliente') { ?>
                            <form action="php/compras.php" method="POST">
                                <!-- Le paso como campos ocultos el código del producto y el origen del archivo
                                 y a la cantidad le pongo el cod_producto para que sea capaz de ser identificada
                                 por el producto seleccionado por el usuario -->
                                <input type="hidden" name="cod_producto" value="<?php echo htmlspecialchars($producto['cod_producto']); ?>">
                                <input type="hidden" name="origen" value="jerseys">
                                <label for="cantidad_<?php echo htmlspecialchars($producto['cod_producto']); ?>">Cantidad</label>
                                <input type="text" class="form-control mb-2" name="cantidad" id="cantidad_<?php echo htmlspecialchars($producto['cod_producto']); ?>" min="1" value="1" required>
                                <button type="submit" class="btn btn-success w-100">Comprar</button>
                            </form>
                        <?php }
                        // FIN BOTÓN COMPRAR
                        // INICIO BOTONES EDITAR Y ELIMINAR
                        // Si existe el usuario y el rol es administrador, le aparecerán los botones de editar y eliminar en cada producto 
                        if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                            <div class="d-flex gap-1 mt-2">
                                <!-- El data-bs-target hace referencia al id del modal editar, para que pueda ser identificado de 
                                 forma única sin problemas y lleva el código del producto para que la identificación sea exitosa y no tuviera
                                 ningún tipo de error -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editar_<?php echo $identificadorModal; ?>">
                                    Editar
                                </button>
                                <!-- El data-bs-target hace referencia al id del modal eliminar, para que pueda ser identificado de 
                                 forma única sin problemas y lleva el código del producto para que la identificación sea exitosa y no tuviera
                                 ningún tipo de error -->
                                <!-- Botón Eliminar que abre el modal -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminar_<?php echo $identificadorModal; ?>">
                                    Eliminar
                                </button>
                            </div>
                        <?php } ?>
                        <!-- Fin Botones Editar y Eliminar -->

                        <!-- Modal Información Producto -->
                        <!-- Como se puede observar el id es el mismo que el data-bs-target que aparece en el botón más información
                        para que me abra el modal una vez el usuario pulse el botón más información -->
                        <div class="modal fade" id="<?php echo $identificadorModal; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Información del producto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="card mb-3" style="max-width: 700px;">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-md-4 d-flex justify-content-center align-items-center bg-light">
                                                        <img src="../<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen del producto" class="img-fluid rounded-start" style="max-height: 200px; object-fit: contain;">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?php echo htmlspecialchars($producto['cod_producto']); ?></h5>
                                                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($producto['nombre']); ?></p>
                                                            <p><strong>Modelo:</strong> <?php echo htmlspecialchars($producto['modelo']); ?></p>
                                                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                                            <p><strong>Precio:</strong> <?php echo htmlspecialchars($producto['precio']) . " €"; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Final Modal Mostrar Información -->

                        <!-- Modal Editar Producto -->
                        <!-- Como se puede observar el id es el mismo que el data-bs-target que aparece en el botón editar
                        para que me abra el modal una vez el usuario pulse el botón editar -->
                        <div class="modal fade" id="editar_<?php echo $identificadorModal; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <!-- En el id del formulario le paso un id con el cod_producto para que el formulario sea identificado de forma única
                                    después paso como campos ocultos el origen del formulario y el cod_producto para que sea identificado
                                    el producto de forma única, meto el id el cod_producto en los otros campos que aparecen en edición, es decir,
                                    le meto el cod_producto para que todos los campos sean identificados de forma correcta y evitando
                                    posibles fallos en los campos ya que tenemos los mismos modales en diferentes archivos 
                                    y hago lo mismo con los botones, le meto el cod_producto para que a la hora de la validación no de problemas -->
                                    <form action="php/editarProducto.php" id="formularioEditarProducto_<?php echo htmlspecialchars($producto['cod_producto']); ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="origen" value="jerseys">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Producto</h5>
                                            <button type="button" id="botonEditarCerrar_<?php echo htmlspecialchars($producto['cod_producto']); ?>" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="cod_producto" value="<?php echo htmlspecialchars($producto['cod_producto']); ?>">

                                            <div class="mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="editarNombre_<?php echo htmlspecialchars($producto['cod_producto']); ?>" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Modelo</label>
                                                <input type="text" class="form-control" id="editarModelo_<?php echo htmlspecialchars($producto['cod_producto']); ?>" name="modelo" value="<?php echo htmlspecialchars($producto['modelo']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Descripción</label>
                                                <textarea class="form-control" id="editarDescripcion_<?php echo htmlspecialchars($producto['cod_producto']); ?>" name="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Precio (sin € y los decimales con .)</label>
                                                <input type="text" class="form-control" id="editarPrecio_<?php echo htmlspecialchars($producto['cod_producto']); ?>" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="imagen" class="form-label">Seleccionar imagen</label>
                                                <input type="file" class="form-control" id="editarImagen_<?php echo htmlspecialchars($producto['cod_producto']); ?>" name="imagen" accept="image/jpeg, image/png, image/jpg">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" id="botonEditarEnviar_<?php echo htmlspecialchars($producto['cod_producto']); ?>" class="btn btn-primary">Guardar cambios</button>
                                            <button type="button" id="botonEditarCancelar_<?php echo htmlspecialchars($producto['cod_producto']); ?>" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        </div><br>
                                        <!-- Aquí muestro los mensaje de error de la validación de JS -->
                                        <div id="resultadoEditarProducto_<?php echo htmlspecialchars($producto['cod_producto']); ?>" style="color: red; margin-bottom: 10px; text-align: center;">

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Final Modal Editar Producto -->
                        <!-- Para evitar problemas con la carga le digo que me cargue los archivos cuando el html esté listo -->
                        <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    iniciarEditarProducto('<?php echo htmlspecialchars($producto['cod_producto']); ?>');
                                });
                            </script>
                        <?php } ?>
                    </div>
                    <!-- Modal Confirmación Eliminación -->
                    <!-- En eliminar paso como campos ocultos el origen del archivo y el cod_producto para que me identifique
                    el producto y pueda ser borrado -->
                    <div class="modal fade" id="eliminar_<?php echo $identificadorModal; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="php/eliminarProducto.php" method="POST">
                                    <input type="hidden" name="cod_producto" value="<?php echo htmlspecialchars($producto['cod_producto']); ?>">
                                    <input type="hidden" name="origen" value="jerseys">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirmar eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p>¿Estás seguro de que deseas eliminar el producto <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>?</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
        <!-- aquí incluyo los scripts de validación en JS -->
        <script src="../JavaScript/validacionFormularioAñadirProducto.js"></script>
        <script src="../JavaScript/validacionFormularioEditarProducto.js"></script>
    <?php } ?>

</body>

</html>