<?php
date_default_timezone_set('Europe/Madrid');
session_start();
require_once 'Conexion.php';

if (!isset($_SESSION['identificadorUsuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

$dni = $_SESSION['identificadorUsuario'];
$conexion = Conexion::conexionBD();

$sql = "SELECT c.cod_producto, p.nombre, c.cantidad, c.subtotal, c.fecha_compra, p.imagen
        FROM Compran c
        INNER JOIN Productos p ON c.cod_producto = p.cod_producto
        WHERE c.dni = ?
        ORDER BY c.fecha_compra DESC";

$resultadoPedidos = $conexion->prepare($sql);
$resultadoPedidos->bind_param("s", $dni);
$resultadoPedidos->execute();
$resultado = $resultadoPedidos->get_result();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Mis compras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/pedidos.css" />
</head>

<body>

    <!-- Enlace de volver al inicio -->
    <a href="../index.php" class="volver-menu-principal">
        ← Volver al Inicio
    </a>

    <main class="container py-5">
        <div class="bg-white p-4 rounded shadow mb-5">
            <h2 class="text-center m-0">Mis Pedidos</h2>
        </div>


        <?php if ($resultado->num_rows === 0) { ?>
            <div class="d-flex justify-content-center align-items-center" style="min-height: 300px;">
                <div class="text-center">
                    <div class="alert alert-info" role="alert">
                        No has realizado ninguna compra todavía.
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php while ($compras = $resultado->fetch_assoc()) { ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="../<?php echo htmlspecialchars($compras['imagen']); ?>" class="card-img-top" alt="Imagen de <?php echo htmlspecialchars($compras['nombre']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($compras['nombre']); ?></h5>
                                <p class="card-text"><strong>Código:</strong> <?php echo htmlspecialchars($compras['cod_producto']); ?></p>
                                <p class="card-text"><strong>Cantidad:</strong> <?php echo $compras['cantidad']; ?></p>
                                <p class="card-text"><strong>Subtotal:</strong> <?php echo number_format($compras['subtotal'], 2, ',', '.'); ?> €</p>
                                <p class="card-text"><strong>Fecha de compra:</strong> <?php echo date('d/m/Y H:i:s', strtotime($compras['fecha_compra'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>