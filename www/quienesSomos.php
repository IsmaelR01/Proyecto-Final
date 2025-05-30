<?php
session_start();
require_once 'php/Conexion.php';
// Creo una variable para la conexión con la base de datos
$conexionBaseDatos = Conexion::conexionBD();
// Consulto todos los datos de la tabla proveedores
$consultaProveedores = $conexionBaseDatos->prepare("SELECT CIF, nombre_proveedor, direccion_proveedor,telefono FROM Proveedores");
$consultaProveedores->execute();
$resultado = $consultaProveedores->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos - Tienda de Jerseys y Chaquetas de Punto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estiloQuienesSomos.css">
</head>

<body>
    <?php include 'php/navbar.php'; ?>
    <main class="container my-5">

        <h2 class="section-title">Quiénes Somos</h2>
        <!-- Meto un breadcrumb para que el usuario pueda volver al inicio -->
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quienes Somos</li>
            </ol>
        </nav>
        <div class="col-12 col-md-8 mx-auto mb-5 px-3">
            <div class="card shadow-sm rounded-4 bg-light border-0">
                <div class="card-body text-center">
                    <h4 class="card-title mb-3">Nuestra Historia</h4>
                    <p class="card-text lead">
                        Fundada en 2023, <strong>Mundo Punto</strong> nació con el propósito de ofrecer prendas exclusivas de alta calidad en
                        <strong>géneros de punto</strong>. Desde nuestra pequeña tienda online, estamos intentando expandir nuestra oferta de <strong>jerseys y chaquetas
                        </strong> elaborados con materiales seleccionados cuidadosamente, que garantizan comodidad y estilo para nuestros clientes.
                    </p>
                    <p class="intro-text mt-3 fst-italic">
                        “Con cada prenda, buscamos no solo vestir, sino también contar una historia de confort, calidad y diseño”.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8 mx-auto mb-5 px-3">
            <div class="card shadow-sm rounded-4 bg-light border-0">
                <div class="card-body text-center">
                    <h4 class="card-title mb-3">Misión</h4>
                    <p class="card-text lead">
                        Nuestra misión es ser el destino favorito para quienes buscan <strong>prendas de punto de calidad premium</strong>, elaboradas con tejidos
                        suaves, duraderos y de diseño moderno. Queremos que cada cliente se sienta cómodo y elegante al usar nuestros productos, brindando una
                        experiencia de compra única.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8 mx-auto mb-5 px-3">
            <div class="card shadow-sm rounded-4 bg-light border-0">
                <div class="card-body text-center">
                    <h4 class="card-title mb-3">Visión a Futuro</h4>
                    <p class="card-text lead">
                        En los próximos años, aspiramos a seguir creciendo como una marca <strong>sostenible y responsable</strong>, trabajando de la mano
                        con <strong>proveedores locales</strong> e internacionales que compartan nuestra visión de ofrecer productos de alta calidad y amigables
                        con el medio ambiente. Queremos expandir nuestra presencia online y llegar a más clientes que busquen prendas de punto versátiles,
                        para todas las ocasiones.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-10 mx-auto">
            <h2 class="providers-title">Nuestros Proveedores</h2>
            <!-- aquí muestro los mensajes de error o éxito que vienen del servisor -->
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

            <div class="row g-4">
                <!-- INICIO: Botón Añadir Proveedor -->
                <!-- Botón para abrir modal de añadir proveedor y en el data-bs-target paso el identificaodr del
                modal añadir para que cuando el usuario pulse el botón de añadir sepa identificar el modal sin problemas -->
                <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                    <div class="mb-3">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#añadirProveedorModal">
                            Añadir Proveedor
                        </button>
                    </div>
                <?php } ?>
                <!-- FIN: Botón Añadir Proveedor -->

                <!-- INICIO: Modal Añadir Proveedor -->
                <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                    <div class="modal fade" id="añadirProveedorModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="php/añadirProveedor.php" id="formularioAñadirProveedor" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Añadir Proveedor</h5>
                                        <button type="button" id="botonAñadirCerrarProveedor" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">CIF</label>
                                            <input type="text" id="añadirCIF" name="CIF" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" id="añadirNombre_proveedor" name="nombre_proveedor" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" id="añadirDireccion_proveedor" name="direccion_proveedor" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" id="añadirTelefono" name="telefono" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="botonAñadirEnviarProveedor" name="añadirEnviarProveedor" class="btn btn-success">Guardar</button>
                                        <button type="button" id="botonAñadirCancelarProveedor" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div><br>
                                    <!-- Aquí muestro los errrores que vienen de JS -->
                                    <div id="resultadoProveedor" style="color: red; margin-bottom: 10px; text-align: center;">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- FIN: Modal Añadir Proveedor -->
                <!-- Recorro las filas de la consulta para extraer los datos -->
                <?php while ($proveedor = $resultado->fetch_assoc()) {  ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($proveedor['direccion_proveedor']); ?></p>
                                <!-- INICIO: Botones editar/eliminar -->
                                <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Botón Editar -->
                                        <!-- El data-bs-target hace referencia al id del modal editar, para que pueda ser identificado de 
                                        forma única sin problemas y lleva el CIF del proveedor para que la identificación sea exitosa y no tuviera
                                        ningún tipo de error -->
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarProveedor_<?php echo $proveedor['CIF']; ?>">
                                            Editar
                                        </button>
                                        <!-- Botón Eliminar que abre el modal -->
                                        <!-- El data-bs-target hace referencia al id del modal eliminar, para que pueda ser identificado de 
                                        forma única sin problemas y lleva el CIF del proveedor para que la identificación sea exitosa y no tuviera
                                        ningún tipo de error -->
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarProveedor_<?php echo htmlspecialchars($proveedor['CIF']); ?>">
                                            Eliminar
                                        </button>
                                    </div>
                                <?php } ?>
                                <!-- FIN: Botones editar/eliminar -->
                            </div>
                        </div>
                    </div>

                    <!-- INICIO: Modal Editar Proveedor -->
                    <div class="modal fade" id="editarProveedor_<?php echo htmlspecialchars($proveedor['CIF']); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <!-- En el id del formulario le paso un id con el CIF para que el formulario sea identificado de forma única
                                después paso como campo ocultos el CIF para que sea identificado el proveedor de forma única, 
                                meto el id el CIF en los otros campos que aparecen en edición, es decir, le meto el CIF del proveedor para que 
                                todos los campos sean identificados de forma correcta y hago lo mismo con los botones, le meto el CIF del proveedor 
                                para que a la hora de la validación no de problemas -->
                                <form action="php/editarProveedor.php" id="formularioEditarProveedor_<?php echo htmlspecialchars($proveedor['CIF']); ?>" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Proveedor</h5>
                                        <button type="button" id="botonEditarCerrar_<?php echo htmlspecialchars($proveedor['CIF']); ?>" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="CIF" value="<?php echo htmlspecialchars($proveedor['CIF']); ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" id="editarNombre_proveedor_<?php echo htmlspecialchars($proveedor['CIF']); ?>" name="nombre_proveedor" class="form-control" value="<?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" id="editarDireccion_proveedor_<?php echo htmlspecialchars($proveedor['CIF']); ?>" name="direccion_proveedor" class="form-control" value="<?php echo htmlspecialchars($proveedor['direccion_proveedor']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" id="editarTelefono_<?php echo htmlspecialchars($proveedor['CIF']); ?>" name="telefono" class="form-control" value="<?php echo htmlspecialchars($proveedor['telefono']); ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="botonEditarEnviar_<?php echo htmlspecialchars($proveedor['CIF']); ?>" class="btn btn-primary">Guardar Cambios</button>
                                        <button type="button" id="botonEditarCancelar_<?php echo htmlspecialchars($proveedor['CIF']); ?>" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div><br>
                                    <div id="resultadoEditarProveedor_<?php echo htmlspecialchars($proveedor['CIF']); ?>" style="color: red; margin-bottom: 10px; text-align: center;">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Para evitar problemas con la carga le digo que me cargue los archivos cuando el html esté listo -->
                    <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                iniciarEditarProveedor('<?php echo htmlspecialchars($proveedor['CIF']); ?>');
                            });
                        </script>
                    <?php } ?>
                    <!-- FIN: Modal Editar Proveedor -->

                    <!-- Modal Confirmación Eliminar Proveedor -->
                    <!-- En eliminar paso como campo ocultos el CIF del proveedor para que me identifique
                    el proveedor y pueda ser borrado. En el id del formulario le paso un id con el CIF para que el formulario sea identificado de forma única
                    después paso como campo oculto el CIF para que sea identificado el proveedor de forma única y el id del modal
                    coincide con el data-bs-target que he puesto en el botón eliminar para que inicie el modal al pulsar el botón -->
                    <div class="modal fade" id="eliminarProveedor_<?php echo htmlspecialchars($proveedor['CIF']); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="php/eliminarProveedor.php" method="POST">
                                    <input type="hidden" name="CIF" value="<?php echo htmlspecialchars($proveedor['CIF']); ?>">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirmar eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Estás seguro de que deseas eliminar al proveedor <strong><?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?></strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
        <!-- aquí incluyo los scripts de validación en JS -->
        <script src="JavaScript/validacionFormularioAñadirProveedor.js"></script>
        <script src="JavaScript/validacionFormularioEditarProveedor.js"></script>
    <?php } ?>
</body>

</html>