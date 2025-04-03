<?php
$host = 'localhost';
$port = '3310'; // Puerto corregido
$dbname = 'bd_apuesta';
$username = 'root';
$password = ''; // Si tienes contraseña, agrégala aquí

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
