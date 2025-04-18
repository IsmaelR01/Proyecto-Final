<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesional</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'php/navbar.php'; ?>

    <div class="container text-center mt-5">
        <h1>Mi Trayectoria Profesional</h1>
        <p>Mi trayectoria profesional ha sido bastante corta, por ahora debido a que todavía estoy en proceso de aprendizaje
            haciendo lo que más me gusta que es informática en la rama de Desarrollo de Aplicaciones Web.
            He trabajado en una empresa textil durante un año y me ha servido para introducirme en el mundo laboral y después trabajé
            durante 3 meses en una gasolinera en los meses del verano de 2023.
        </p>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Formación Académica</h3>
                <ul class="list-group">
                    <li class="list-group-item">Infantil - C.E.I.P. San Sebastián Localidad: Fiñana (Almería) Años: 2004-2006</li>
                    <li class="list-group-item">Primaria - C.E.I.P. Joaquín Tena Sicilia Localidad: Abla (Almería) Años: 2007-2012</li>
                    <li class="list-group-item">Educación Secundaria Obligatoria - I.E.S. Sierra Nevada Localidad: Fiñana (Almería) Años: 2013-2016</li>
                    <li class="list-group-item">Bachillerato - I.E.S. Sierra Nevada Localidad: Fiñana (Almería) Años: 2017-2019</li>
                    <li class="list-group-item">Ingeniería Informática - Universidad de Almería (abandoné la carrera)  Localidad: Almería Años: 2019-2021</li>
                    <li class="list-group-item">Ciclo Formativo Grado Superior Desarrollo Aplicaciones Web (DAW) - I.E.S.Aguadulce Localidad: Aguadulce (Almería) Años: 2023-Act</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h3>Experiencia Laboral</h3>
                <ul class="list-group">
                    <li class="list-group-item">Rivaspunt S.A. (Empresa Textil)</li>
                    <li class="list-group-item">Gasóleos Huéneja S.L.L. (Gasolinera)</li>
                </ul>
            </div>
        </div>
    </div>

    <?php include 'php/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>