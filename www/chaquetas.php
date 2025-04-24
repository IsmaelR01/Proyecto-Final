<?php
session_start();
require_once 'php/Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
$busqueda = 'C%';
$consultaProducto = $conexionBaseDatos->prepare("SELECT * FROM Productos WHERE cod_producto like ?");
$consultaProducto->bind_param("s", $busqueda);
$consultaProducto->execute();
$resultado = $consultaProducto->get_result();

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

    <main class=container>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chaquetas</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap gap-4 justify-content-start my-4">
            <?php while ($producto = $resultado->fetch_assoc()) { 
                $identificadorModal = "modal_" . htmlspecialchars($producto['cod_producto']);
            ?>
                <div class="card" style="width: 18rem;">
                    <img src="<?php echo htmlspecialchars($producto['imagen']) ?>" class="card-img-top" alt="Imagen del producto">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['cod_producto']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                        <button class="btn btn-success btn-info-producto" data-bs-toggle="modal" data-bs-target="#<?php echo $identificadorModal; ?>">
                            Más información
                        </button>

                        <div class="modal fade" id="<?php echo $identificadorModal; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="<?php echo $identificadorModal; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="<?php echo $identificadorModal; ?>">Información del producto</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container my-5">
                                            <div class="card mb-3" style="max-width: 700px;">
                                                <div class="row g-0 align-items-center" style="min-height: 300px;">
                                                    <div class="col-md-4 d-flex justify-content-center align-items-center" style="background-color: #f8f9fa;">
                                                        <img src="../<?php echo htmlspecialchars($producto['imagen']); ?>"
                                                            alt="Imagen del producto"
                                                            class="img-fluid rounded-start"
                                                            style="max-height: 200px; max-width: 100%; object-fit: contain;">
                                                    </div>

                                                    <div class="col-md-8">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?php echo htmlspecialchars($producto['cod_producto']); ?></h5>
                                                            <p class="card-text"><strong>Nombre del producto:</strong> <?php echo htmlspecialchars($producto['nombre']); ?></p>
                                                            <p class="card-text"><strong>Modelo:</strong> <?php echo htmlspecialchars($producto['modelo']); ?></p>
                                                            <p class="card-text"><strong>Descripción del producto:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
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