<?php
// index.php: Front Controller
session_start();

// Incluir archivos de configuración y funciones de enrutamiento
require_once 'config/db.php';

// Definir la URL base
$base_url = '/bd_apuesta/';

// Obtener la ruta solicitada
$ruta = isset($_GET['url']) ? $_GET['url'] : 'home';

// Enrutamiento simple
switch ($ruta) {
    case 'home':
        require_once 'views/home.php';
        break;
        case 'login':
            require_once 'login.php'; // Ahora está en la raíz del proyecto
            break;
        case 'registro':
            require_once 'registro.php'; // También está en la raíz
            break;
        
    default:
        require_once 'views/404.php';
        break;
}
