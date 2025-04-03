<?php
session_start();

$base_url = "http://localhost:8888/apuesta_por_ti/";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apuesta Por Ti</title>
</head>
<body>
    <h1>Bienvenido a Apuesta Por Ti</h1>
    <a href="<?= $base_url ?>index.php?url=login">Iniciar sesión</a>
    <a href="<?= $base_url ?>index.php?url=registro">Registrarse</a>

    <?php
    if (isset($_GET['url'])) {
        $url = $_GET['url'];

        if ($url == "login") {
            include 'views/login.php';
        } elseif ($url == "registro") {
            include("registro.php");

        }
    }
    ?>
</body>
</html>
