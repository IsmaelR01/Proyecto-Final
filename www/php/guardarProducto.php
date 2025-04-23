<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cod_producto'])) {
    $_SESSION['cod_producto'] = $_POST['cod_producto'];
    http_response_code(200); // OK
} else {
    http_response_code(400); // Bad Request
    echo "Solicitud no válida";
}

