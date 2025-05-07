<?php
require_once(__DIR__ . '/../config/db.php');

class Conversacion {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function crear($paciente_id, $profesional_id) {
        $stmt = $this->db->prepare("INSERT INTO conversaciones (paciente_id, profesional_id) VALUES (?, ?)");
        return $stmt->execute([$paciente_id, $profesional_id]);
    }

    public function obtenerPorPaciente($paciente_id) {
        $stmt = $this->db->prepare("SELECT * FROM conversaciones WHERE paciente_id = ?");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorProfesional($profesional_id) {
        $stmt = $this->db->prepare("SELECT * FROM conversaciones WHERE profesional_id = ?");
        $stmt->execute([$profesional_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id) {
        $stmt = $this->db->prepare("SELECT * FROM conversaciones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
