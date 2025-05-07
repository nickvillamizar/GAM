<?php
require_once(__DIR__ . '/../config/db.php');  // Conexión a la base de datos
require_once(__DIR__ . '/../models/Conversacion.php');
require_once(__DIR__ . '/../models/Mensaje.php');


// Lógica para mostrar las conversaciones del profesional (usuario)
$usuario_id = 2; // Esto lo obtienes del sistema de autenticación o sesión

// Obtener las conversaciones del usuario
$conversacionModel = new Conversacion();
$conversaciones = $conversacionModel->obtenerPorUsuario($usuario_id);

// Mostrar conversaciones
echo "<h2>Mis Conversaciones</h2>";
foreach ($conversaciones as $conversacion) {
    echo "<p>Conversación con usuario ID: " . ($conversacion['usuario_1_id'] == $usuario_id ? $conversacion['usuario_2_id'] : $conversacion['usuario_1_id']) . "</p>";
    echo "<a href='profesional_dashboard.php?conversacion_id=" . $conversacion['id'] . "'>Ver Conversación</a><br>";
}

// Si se accede a una conversación específica
if (isset($_GET['conversacion_id'])) {
    $conversacion_id = $_GET['conversacion_id'];
    $mensajeModel = new Mensaje();
    $mensajes = $mensajeModel->obtenerPorConversacion($conversacion_id);

    echo "<h3>Mensajes</h3>";
    foreach ($mensajes as $mensaje) {
        echo "<p><strong>" . ($mensaje['remitente_id'] == $usuario_id ? 'Tú' : 'Otro Usuario') . ":</strong> " . $mensaje['contenido'] . "</p>";
    }

    // Formulario para enviar un nuevo mensaje
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['contenido'])) {
        $contenido = $_POST['contenido'];
        $remitente_id = $usuario_id;
        $mensajeModel->enviar($conversacion_id, $remitente_id, $contenido);
        header("Location: profesional_dashboard.php?conversacion_id=" . $conversacion_id); // Recargar para mostrar el nuevo mensaje
    }

    echo "<form method='POST'>
            <textarea name='contenido'></textarea>
            <button type='submit'>Enviar mensaje</button>
          </form>";
}
?>
