<?php
$servername = "127.0.0.1:3310"; // O "localhost" si es correcto
$username = "root"; // Ajusta si usas otro usuario
$password = ""; // Ajusta si tienes contraseña
$dbname = "bd_apuesta"; // Reemplaza con el nombre correcto

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
