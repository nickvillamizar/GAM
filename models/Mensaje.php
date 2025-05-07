<?php
require_once(__DIR__ . '/../config/db.php');  // Conexión a la base de datos

class Mensaje {
    private $db;

    public function __construct() {
        global $pdo; // Accedemos a la variable $pdo del archivo db.php
        $this->db = $pdo;
    }

    public function enviar($conversacion_id, $remitente_id, $contenido) {
        $stmt = $this->db->prepare("INSERT INTO mensajes (conversacion_id, remitente_id, contenido) VALUES (?, ?, ?)");
        return $stmt->execute([$conversacion_id, $remitente_id, $contenido]);
    }

    public function obtenerPorConversacion($conversacion_id) {
        $stmt = $this->db->prepare("SELECT * FROM mensajes WHERE conversacion_id = ? ORDER BY fecha_envio ASC");
        $stmt->execute([$conversacion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
