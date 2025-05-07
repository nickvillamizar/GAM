<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'listar') {
    $stmt = $pdo->prepare("SELECT c.*, u.nombre_completo 
        FROM citas c JOIN profesionales p ON c.profesional_id=p.id
                     JOIN usuarios u ON p.usuario_id=u.id
        WHERE paciente_id = ?");
    $stmt->execute([$_GET['paciente_id']]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'agendar') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO citas (paciente_id, profesional_id, fecha, duracion, modalidad, estado)
                           VALUES (?, ?, ?, ?, ?, 'Pendiente')");
    $stmt->execute([
      $data['paciente_id'], $data['profesional_id'], $data['fecha'],
      $data['duracion'], $data['modalidad']
    ]);
    echo json_encode(['success'=>true]);
}

// Agregar más acciones: cancelar, etc.
