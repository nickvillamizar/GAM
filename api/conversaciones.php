<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'listar') {
    $user = $_GET['user_id'];
    $stmt = $pdo->prepare("SELECT c.id, u.id AS otro_id, u.nombre_completo
        FROM conversaciones c
        JOIN usuarios u
          ON u.id = IF(c.paciente_id = ?, c.profesional_id, c.paciente_id)
        WHERE c.paciente_id = ? OR c.profesional_id = ?");
    $stmt->execute([$user, $user, $user]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'crear') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT IGNORE INTO conversaciones (paciente_id, profesional_id) VALUES (?, ?)");
    $stmt->execute([$data['paciente_id'], $data['profesional_id']]);
    echo json_encode(['success'=>true,'id'=>$pdo->lastInsertId()]);
}
