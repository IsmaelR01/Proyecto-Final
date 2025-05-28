<?php
date_default_timezone_set('Europe/Madrid');
session_start();
require_once 'Conexion.php';
// Si no existe la sesión con el dni del usuario o el rol del usuario no es el de cliente, lo redirijo a login.php
if (!isset($_SESSION['identificadorUsuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}
// Me guardo el dni de la sesión del usuario en una variable
$dni = $_SESSION['identificadorUsuario'];
// Creo una variable para la conexión con la base de datos
$conexion = Conexion::conexionBD();
// Meto la consulta en una variable, en este caso uno las tablas Compran y Productos por sus claves externas
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

        <!-- Si el numero de filas es 0 quiere decir que no hay ninguna compra por lo que le muestro un mensaje informativo -->
        <?php if ($resultado->num_rows === 0) { ?>
            <div class="d-flex justify-content-center align-items-center" style="min-height: 300px;">
                <div class="text-center">
                    <div class="alert alert-info" role="alert">
                        No has realizado ninguna compra todavía.
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <!-- Si el número de filas es mayor que 0 muestro los productos con sus datos en cards -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php while ($compras = $resultado->fetch_assoc()) { ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="../<?php echo htmlspecialchars($compras['imagen']); ?>" class="card-img-top" alt="Imagen de <?php echo htmlspecialchars($compras['nombre']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($compras['nombre']); ?></h5>
                                <p class="card-text"><strong>Código:</strong> <?php echo htmlspecialchars($compras['cod_producto']); ?></p>
                                <p class="card-text"><strong>Cantidad:</strong> <?php echo $compras['cantidad']; ?></p>
                                <!-- Aquí muestro el subtotal con los decimales con , -->
                                <p class="card-text"><strong>Subtotal:</strong> <?php echo number_format($compras['subtotal'], 2, ',', '.'); ?> €</p>
                                <!-- Aquí muestro la fecha y hora de la compra y lo muestro en formato español, para ello convierto la fecha de texto a formato UNIX
                                 para que sea entendible por la base de datos -->
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