<?php
session_start();
require_once 'php/Conexion.php';
$conexionBaseDatos = Conexion::conexionBD();
$anioActual = date("Y");
$consultaNovedades = $conexionBaseDatos->prepare("SELECT imagen FROM Productos WHERE modelo = ? ORDER BY modelo DESC");
$consultaNovedades->bind_param("s", $anioActual);
$consultaNovedades->execute();
$resultado = $consultaNovedades->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Web Personal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'php/navbar.php'; ?>

    <main class="container">

        <h1 class="text-center my-4">Ofertas y Promociones</h1>
        <div class="d-flex justify-content-center">
            <div class="card overflow-hidden" style="width: 30rem; height: 30rem;">
                <div id="carouselExample" class="carousel slide h-100">
                    <div class="carousel-inner h-100">
                        <div class="carousel-item active h-100">
                            <div class="h-100 d-flex flex-column">
                                <div class="flex-grow-1 overflow-hidden">
                                    <img src="../imagenes/productos/jersey8.jpg" class="w-100 h-100 object-fit-cover" alt="...">
                                </div>
                                <div class="bg-light py-2">
                                    <p class="text-center m-0">50% de descuento en este bonito jersey.</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item h-100">
                            <div class="h-100 d-flex flex-column">
                                <div class="flex-grow-1 overflow-hidden">
                                    <img src="../imagenes/productos/chaqueta8.jpg" class="w-100 h-100 object-fit-cover" alt="...">
                                </div>
                                <div class="bg-light py-2">
                                    <p class="text-center m-0">25% de descuento en este bonito abrigo. Ideal para días fríos</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item h-100">
                            <div class="h-100 d-flex flex-column">
                                <div class="flex-grow-1 overflow-hidden">
                                    <img src="../imagenes/productos/jersey9.jpg" class="w-100 h-100 object-fit-cover" alt="...">
                                </div>
                                <div class="bg-light py-2">
                                    <p class="text-center m-0">50% de descuento en este bonito jersey.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-black" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-black" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </div>

        <h1 class="text-center my-4">Novedades de este año</h1>
        <div class="d-flex justify-content-center">
            <div class="card overflow-hidden" style="width: 30rem; height: 30rem;">
                <div id="carouselSegundo" class="carousel slide h-100">
                    <div class="carousel-inner h-100">
                        <?php 
                        $primera = true;
                        while ($producto = $resultado->fetch_assoc()) { 
                        ?>
                            <div class="carousel-item <?php if ($primera) { echo 'active'; $primera = false; } ?> h-100">
                                <div class="h-100 d-flex flex-column">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="w-100 h-100 object-fit-cover" alt="...">
                                    </div>
                                    <div class="bg-light py-2">
                                        <p class="text-center m-0">¡Producto nuevo de este año!</p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselSegundo" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-black" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselSegundo" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-black" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </div><br>

        <div class="d-flex justify-content-center gap-3">
            <a href="jerseys.php" class="btn btn-primary btn-lg">Acceso a jerseys</a>
            <a href="chaquetas.php" class="btn btn-primary btn-lg">Acceso a chaquetas</a>
        </div>

        
    </main>

    <?php include 'php/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
