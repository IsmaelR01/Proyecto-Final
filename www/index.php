<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Web Personal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'php/navbar.php'; ?>

    <main class="container">
    <h1 class="text-center my-4">Ofertas y Promociones</h1>

<div id="carruselProductos" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">

        <!-- Primer producto -->
        <div class="carousel-item active">
            <div class="d-flex flex-column align-items-center">
                <img src="imagenes/jersey1.jpg" class="d-block w-50 rounded" alt="Jersey de lana gris" width="800" height="800">
                <div class="carousel-caption d-none d-md-block text-dark">
                    <h5>Jersey Lana Gris</h5>
                    <p>30% de descuento solo esta semana.</p>
                </div>
            </div>
        </div>

        <!-- Segundo producto -->
        <div class="carousel-item">
            <div class="d-flex flex-column align-items-center">
                <img src="imagenes/chaqueta1.jpg" class="d-block w-50 rounded" alt="Chaqueta de punto larga" width="800" height="800">
                <div class="carousel-caption d-none d-md-block text-dark">
                    <h5>Chaqueta de Punto Larga</h5>
                    <p>¡Compra 2 y llévate un gorro gratis!</p>
                </div>
            </div>
        </div>

        <!-- Tercer producto -->
        <div class="carousel-item">
            <div class="d-flex flex-column align-items-center">
                <img src="imagenes/jersey2.jpg" class="d-block w-50 rounded" alt="Jersey tejido artesanal">
                <div class="carousel-caption d-none d-md-block text-dark">
                    <h5>Jersey Artesanal</h5>
                    <p>Hecho a mano, 15% de descuento por lanzamiento.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Controles -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carruselProductos" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carruselProductos" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
    </main>

    <?php include 'php/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
