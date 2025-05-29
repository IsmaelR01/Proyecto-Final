<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estiloQuienesSomos.css">
</head>
<body>
    <?php include 'php/navbar.php'; ?>
    <main class="container-fluid px-3 px-md-5 my-5">
        <h2 class="section-title">Contáctanos</h2>
        <!-- Meto un breadcrumb para que el usuario pueda volver al inicio -->
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contacto</li>
            </ol>
        </nav>
        <div class="row mb-4">
            <!-- Card: Información de contacto -->
            <div class="col-12 col-md-6 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Dirección física</h5>
                        <p class="card-text">Calle Álvaro de Bazán, 22, 18500 Guadix (Granada)</p>
                        <h5 class="card-title"><i class="bi bi-envelope-fill text-danger me-2"></i>Correo electrónico</h5>
                        <p><a href="mailto:ismaelrivasn@gmail.com">ismaelrivasn@gmail.com</a></p>
                        <h5 class="card-title"><i class="bi bi-telephone-fill text-success me-2"></i>Teléfono</h5>
                        <p><a href="tel:+34625559655">+34 625 55 96 55</a></p>
                        <h5 class= "card-title">Información de interés</h5>
                        <p class="card-text lead">Para nuestros fieles clientes que no les gustan comprar
                        en línea y que prefieren una experiencia más cercana y personalizada, nuestra
                        tienda física está pensada para ustedes. Aquí podrán ver los productos de primera
                        mano, recibir asesoramiento directo, resolver cualquier tipo de duda y disfrutar
                        de un trato directo y humano.<br>
                        Nuestro horario de atención al público es de Lunes a Viernes de 9:00-14:00 horas 
                        y de 17:00-21:00 horas.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card: Mapa de ubicación -->
            <div class="col-12 col-md-6 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-map-fill text-info me-2"></i>Ubicación en el mapa</h5>
                        <div class="ratio ratio-4x3">
                            <!-- aquí incluyo el mapa -->
                            <iframe src="https://www.google.com/maps/embed?pb=!4v1746097104124!6m8!1m7!1sjWEEeLBtv_nD64CpNcpRgw!2m2!1d37.30089245351443!2d-3.13102701911076!3f264.6652534907467!4f2.4675138556271747!5f0.7820865974627469" 
                                style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Redes sociales -->
        <div class="card shadow-sm rounded-4 bg-light border-0 my-4">
            <div class="card-body text-center">
                <h5 class="card-title">Síguenos en redes sociales</h5>
                <a href="https://www.facebook.com/?locale=es_ES" class="btn btn-outline-primary mx-2 fs-1"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/" class="btn btn-outline-danger mx-2 fs-1"><i class="bi bi-instagram"></i></a>
                <a href="https://x.com/?lang=es" class="btn btn-outline-dark mx-2 fs-1"><i class="bi bi-twitter"></i></a>
            </div>
        </div>

    </main>

    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
