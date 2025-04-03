<?php
// config/db.php
$host     = "localhost";
$usuario  = "root";
$clave    = "";
$bd       = "bd_apuesta";
$puerto   = 3310;

$conn = new mysqli($host, $usuario, $clave, $bd, $puerto);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar charset (opcional pero recomendado)
$conn->set_charset("utf8");
?>
