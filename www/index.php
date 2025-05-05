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


        
    </main>

    <?php include 'php/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
