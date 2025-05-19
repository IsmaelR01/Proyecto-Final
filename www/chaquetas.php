<?php
session_start();
require_once 'php/Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
$busqueda = 'C%';
$consultaProducto = $conexionBaseDatos->prepare("SELECT cod_producto, nombre, modelo, descripcion, imagen, REPLACE(FORMAT(precio, 2), '.', ',') AS precio, CIF FROM Productos WHERE cod_producto like ?");
$consultaProducto->bind_param("s", $busqueda);
$consultaProducto->execute();
$resultado = $consultaProducto->get_result();
$consultaProveedores = $conexionBaseDatos->query("SELECT CIF, nombre_proveedor FROM Proveedores");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Chaquetas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .card {
            border: 2px solid #111;
            transition: box-shadow 0.3s ease, border 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            border: 2px solid #0659b8;
        }

    </style>
</head>
<body>
    <?php include 'php/navbar.php'; ?>  

    <main class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chaquetas</li>
            </ol>
        </nav>

        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <?php if(isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
            <div class="d-flex gap-1 my-3">
                <button type="button" class="btn btn-success btn-crud" data-bs-toggle="modal" data-bs-target="#añadirModal">
                    Añadir producto
                </button>
            </div>

            <!-- Modal Añadir Producto -->
            <div class="modal fade" id="añadirModal" tabindex="-1" aria-labelledby="añadirModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="php/añadirProducto.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="origen" value="chaquetas">
                            <div class="modal-header">
                                <h5 class="modal-title" id="añadirModalLabel">Añadir Nuevo Producto</h5>
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
                                <button type="button" id="botonAñadirCancelar"class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div><br>
                            <div id="resultadoProducto" style="color: red; margin-bottom: 10px; text-align: center;">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Final Modal Añadir Producto -->
        <?php } ?>

        <div class="d-flex flex-wrap gap-4 justify-content-start my-4">
            <?php while ($producto = $resultado->fetch_assoc()) {
                $identificadorModal = "modal_" . htmlspecialchars($producto['cod_producto']);
            ?>
                <!-- Aquí empieza la card y se muestra la información básica, 
                 solo se mostrará el botón más información si se ha iniciado sesión tanto para los clientes como los administradores -->
                <div class="card" style="width: 18rem;">
                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="Imagen del producto">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['cod_producto']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                        <p class="card-text"><?php echo htmlspecialchars($producto['precio']). " €"; ?></p>
                        <?php if (isset($_SESSION['usuario']) && (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'cliente' || $_SESSION['rol'] === 'admin'))) { ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $identificadorModal; ?>">
                            Más información
                        </button><br><br>
                        <?php }
                        if(isset($_SESSION['usuario']) && $_SESSION['rol'] === 'cliente') { ?>
                            <form action="php/compras.php" method="POST">
                                <input type="hidden" name="cod_producto" value="<?php echo htmlspecialchars($producto['cod_producto']); ?>">
                                <input type="hidden" name="origen" value="chaquetas">
                                <label for="cantidad_<?php echo htmlspecialchars($producto['cod_producto']); ?>">Cantidad</label>
                                <input type="text" class="form-control mb-2" name="cantidad" id="cantidad_<?php echo htmlspecialchars($producto['cod_producto']); ?>" min="1" value="1" required>
                                <button type="submit" class="btn btn-success w-100">Comprar</button>
                            </form>
                        <?php }  
                        if(isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                            <div class="d-flex gap-1 mt-2">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editar_<?php echo $identificadorModal; ?>">
                                    Editar
                                </button>
                                <form action="php/eliminarProducto.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');" class="d-inline">
                                    <input type="hidden" name="origen" value="chaquetas">
                                    <input type="hidden" name="cod_producto" value="<?php echo htmlspecialchars($producto['cod_producto']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </div>
                        <?php } ?>

                        <!-- Modal Información Producto -->
                        <div class="modal fade" id="<?php echo $identificadorModal; ?>" tabindex="-1" aria-labelledby="<?php echo $identificadorModal; ?>" aria-hidden="true">
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
                                                            <p><strong>Precio:</strong> <?php echo htmlspecialchars($producto['precio']). " €"; ?></p>
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
                        <div class="modal fade" id="editar_<?php echo $identificadorModal; ?>" tabindex="-1" aria-labelledby="editar_<?php echo $identificadorModal; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="php/editarProducto.php" id="formularioEditarProducto_<?php echo htmlspecialchars($producto['cod_producto']); ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="origen" value="chaquetas">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Producto</h5>
                                            <button type="button" class="btn-close" id="botonEditarCerrar_<?php echo htmlspecialchars($producto['cod_producto']); ?>" data-bs-dismiss="modal"></button>
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
                                                <label class="form-label">Imagen (subir nueva si se desea)</label>
                                                <input type="file" class="form-control" id="editarImagen_<?php echo htmlspecialchars($producto['cod_producto']); ?>" name="imagen" accept="image/jpeg, image/png, image/jpg">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" id="botonEditarEnviar_<?php echo htmlspecialchars($producto['cod_producto']); ?>" class="btn btn-primary">Guardar cambios</button>
                                            <button type="button" id="botonEditarCancelar_<?php echo htmlspecialchars($producto['cod_producto']); ?>" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        </div><br>
                                        <div id="resultadoEditarProducto_<?php echo htmlspecialchars($producto['cod_producto']); ?>" style="color: red; margin-bottom: 10px; text-align: center;">

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Final Modal Editar Producto -->
                        <!-- Para evitar problemas con la carga le digo que me cargue los archivos cuando estén listos -->
                        <?php if(isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    iniciarEditarProducto('<?php echo htmlspecialchars($producto['cod_producto']); ?>');
                                });
                            </script>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

    </main>

    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if(isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?> 
        <script src="../JavaScript/validacionFormularioAñadirProducto.js"></script>
        <script src="../JavaScript/validacionFormularioEditarProducto.js"></script>
    <?php } ?>
</body>
</html>