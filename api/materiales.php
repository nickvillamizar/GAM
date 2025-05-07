<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'listar') {
    $stmt = $pdo->prepare("SELECT m.*, pm.completado 
        FROM materiales m
        LEFT JOIN progreso_material pm
          ON m.id=pm.material_id AND pm.paciente_id=?
        WHERE m.profesional_id=?");
    $stmt->execute([$_GET['paciente_id'], $_GET['profesional_id']]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'marcar') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO progreso_material (paciente_id, material_id, completado)
                           VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE completado=VALUES(completado)");
    $stmt->execute([$data['paciente_id'], $data['material_id'], $data['completado']]);
    echo json_encode(['success'=>true]);
}
