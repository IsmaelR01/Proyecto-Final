<?php
session_start();
require_once 'php/Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
$busqueda = 'J%';
$consultaProducto = $conexionBaseDatos->prepare("SELECT cod_producto, nombre, imagen FROM Productos WHERE cod_producto like ?");
$consultaProducto->bind_param("s", $busqueda);
$consultaProducto->execute();
$resultado = $consultaProducto->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Jerseys</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'php/navbar.php'; ?>  

    <main class="container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jerseys</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap gap-4 justify-content-start my-4">
            <?php while ($producto = $resultado->fetch_assoc()) { ?>
                <div class="card" style="width: 18rem;">
                    <img src="<?php echo htmlspecialchars($producto['imagen']) ?>" class="card-img-top" alt="Imagen del producto">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['cod_producto']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                        <button class="btn btn-success btn-info-producto" data-codigo="<?php echo htmlspecialchars($producto['cod_producto']); ?>">
                            Más información
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="Javascript/ajaxbusquedaproducto.js"></script>
    
</body>
</html>

