<?php 
session_start();

if (!isset($_SESSION['cod_producto'])) {
    echo "No se ha seleccionado ningún producto.";
    exit;
}

$codProducto = $_SESSION['cod_producto'];
unset($_SESSION['cod_producto']);

require_once 'Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
$consultaProducto = $conexionBaseDatos->prepare("SELECT * FROM Productos WHERE cod_producto = ?");
$consultaProducto->bind_param("s", $codProducto);
$consultaProducto->execute();
$resultado = $consultaProducto->get_result();

$producto = $resultado->fetch_assoc();
if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre']); ?> - Detalles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="../<?php echo htmlspecialchars($producto['imagen']); ?>" class="img-fluid rounded-start" alt="Imagen del producto">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['cod_producto']); ?></h5>
                        <p class="card-text"><strong>Nombre del producto:</strong> <?php echo htmlspecialchars($producto['nombre']); ?></p>
                        <p class="card-text"><strong>Modelo:</strong> <?php echo htmlspecialchars($producto['modelo']); ?></p>
                        <p class="card-text"><strong>Descripción detallada del producto:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
