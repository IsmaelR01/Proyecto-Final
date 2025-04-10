<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Mí</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'php/navbar.php'; ?>  

    <div class="container text-center">
        <h1>Sobre Mí</h1>
        <p>Soy una persona a la que le apasiona el mundo de la informática y en la que en mi tiempo libre 
            me gusta desconectar, estar con familia y amigos y sobre todo jugar tanto al Pádel como al fútbol.
        </p><br>
        <div class="row">
            <div class="col-md-6">
                <h3>Gustos y aficiones</h3><br>
                <ul>
                    <li>Futbol</li>
                    <br><br>
                    <li>Pádel</li>
                    <br><br>
                    <li>Videojuegos</li>
                    <br><br>
                    <li>Leer cómics</li>
                </ul>
            </div>
            <div class="col-md-6">
                <img src="imagenes/futbol.jpg" class="img-fluid" alt="Fútbol">
            </div>
        </div>
    </div>

    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
