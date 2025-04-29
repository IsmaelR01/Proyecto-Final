<?php
session_start();
require_once 'php/Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
$busqueda = 'C%';
$consultaProducto = $conexionBaseDatos->prepare("SELECT * FROM Productos WHERE cod_producto like ?");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
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

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

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
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Código</label>
                                    <input type="text" class="form-control" name="cod_producto" required maxlength="5">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" required maxlength="40">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Modelo</label>
                                    <input type="text" class="form-control" name="modelo" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="descripcion"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="cif" class="form-label">Proveedor</label>
                                    <select name="cif" id="cif" class="form-select" required>
                                        <option value="" selected disabled>Selecciona un proveedor</option>
                                        <?php while ($proveedor = $consultaProveedores->fetch_assoc()): ?>
                                            <option value="<?php echo htmlspecialchars($proveedor['CIF']); ?>">
                                                <?php echo htmlspecialchars($proveedor['nombre_proveedor']) . " (" . htmlspecialchars($proveedor['CIF']) . ")"; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Seleccionar imagen</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/jpeg, image/png" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Añadir Producto</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="d-flex flex-wrap gap-4 justify-content-start my-4">
            <?php while ($producto = $resultado->fetch_assoc()) {
                $identificadorModal = "modal_" . htmlspecialchars($producto['cod_producto']);
            ?>
                <div class="card" style="width: 18rem;">
                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="Imagen del producto">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['cod_producto']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#<?php echo $identificadorModal; ?>">
                            Más información
                        </button>

                        <?php if(isset($_SESSION['usuario']) && $_SESSION['rol'] === 'admin') { ?>
                            <div class="d-flex gap-1 mt-2">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editar_<?php echo $identificadorModal; ?>">
                                    Editar
                                </button>
                                <form action="php/eliminarProducto.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');" class="d-inline">
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                        </div>

                        <!-- Modal Editar Producto -->
                        <div class="modal fade" id="editar_<?php echo $identificadorModal; ?>" tabindex="-1" aria-labelledby="editar_<?php echo $identificadorModal; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="php/editarProducto.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="origen" value="chaquetas">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Producto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="cod_producto" value="<?php echo htmlspecialchars($producto['cod_producto']); ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="nombre" required value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Modelo</label>
                                                <input type="text" class="form-control" name="modelo" required value="<?php echo htmlspecialchars($producto['modelo']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Descripción</label>
                                                <textarea class="form-control" name="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Imagen (subir nueva si se desea)</label>
                                                <input type="file" class="form-control" name="imagen" accept="image/jpeg, image/png">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php } ?>
        </div>

    </main>

    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
