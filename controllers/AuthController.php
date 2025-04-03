<?php
// controllers/AuthController.php

session_start();
require_once '../config/db.php';
require_once '../models/Usuario.php';

// Crear instancia de Usuario
$usuario = new Usuario($conn);

// Verificar si la petición es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validar que los campos no estén vacíos
    if (empty($correo) || empty($password)) {
        $error = "Todos los campos son obligatorios";
        header("Location: ../views/login.php?error=" . urlencode($error));
        exit();
    }

    // Autenticar usuario
    $auth = $usuario->autenticar($correo, $password);
    if ($auth && password_verify($password, $auth['contraseña'])) {
        // Iniciar sesión
        $_SESSION['usuario_id'] = $auth['id'];
        $_SESSION['nombre'] = htmlspecialchars($auth['nombre_completo']);
        
        header("Location: ../index.php?url=dashboard");
        exit();
    } else {
        $error = "Correo o contraseña incorrectos";
        header("Location: ../views/login.php?error=" . urlencode($error));
        exit();
    }
}
?>
