<?php
// index.php: Front Controller
session_start();

// Incluir archivos de configuración y funciones de enrutamiento
require_once 'config/db.php';

// Definir la URL base
$base_url = '/apuesta_por_ti/';

// Obtener la ruta solicitada
$ruta = isset($_GET['url']) ? $_GET['url'] : 'home';

// Enrutamiento simple
switch ($ruta) {
    case 'home':
        require_once 'views/home.php';
        break;
    case 'login':
        require_once 'views/login.php';
        break;
    case 'registro':
        require_once 'views/registro.php';
        break;
    default:
        require_once 'views/404.php';
        break;
}
