<?php
session_start();
require_once 'php/Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
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
                    <div class="modal fade" id="añadirProveedorModal" tabindex="-1" aria-labelledby="añadirProveedorLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="php/añadirProveedor.php" id="formularioAñadirProveedor" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="añadirProveedorLabel">Añadir Proveedor</h5>
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

                                    <div id="resultadoProveedor" style="color: red; margin-bottom: 10px; text-align: center;">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- FIN: Modal Añadir Proveedor -->

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
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarProveedor_<?php echo $proveedor['CIF']; ?>">
                                            Editar
                                        </button>
                                        <!-- Botón Eliminar -->
                                        <form action="php/eliminarProveedor.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este proveedor?');">
                                            <input type="hidden" name="CIF" value="<?php echo htmlspecialchars($proveedor['CIF']); ?>">
                                            <button class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                <?php } ?>
                                <!-- FIN: Botones editar/eliminar -->
                            </div>
                        </div>
                    </div>

                    <!-- INICIO: Modal Editar Proveedor -->
                    <div class="modal fade" id="editarProveedor_<?php echo $proveedor['CIF']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="php/editarProveedor.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Proveedor</h5>
                                        <button type="button" id="botonEditarCerrar" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="CIF" value="<?php echo htmlspecialchars($proveedor['CIF']); ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" id="editarNombre_proveedor" name="nombre_proveedor" class="form-control" value="<?php echo htmlspecialchars($proveedor['nombre_proveedor']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" id="editarDireccion_proveedor" name="direccion_proveedor" class="form-control" value="<?php echo htmlspecialchars($proveedor['direccion_proveedor']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" id="editarTelefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($proveedor['telefono']); ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="botonEditarEnviar" class="btn btn-primary">Guardar Cambios</button>
                                        <button type="button" id="botonEditarCancelar" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div><br>
                                    <div id="resultadoEditarProveedor" style="color: red; margin-bottom: 10px; text-align: center;">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                iniciarEditarProveedor('<?php echo htmlspecialchars($proveedor['CIF']); ?>');
                            });
                        </script>
                    <?php } ?>

                    <!-- FIN: Modal Editar Proveedor -->
                <?php } ?>
            </div>
        </div>
    </main>

    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
        <script src="JavaScript/validacionFormularioAñadirProveedor.js"></script>
        <script src="JavaScript/validacionFormularioEditarProveedor.js"></script>
    <?php } ?>
</body>

</html>