<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? '';

if ($action === 'listar') {
    $stmt = $pdo->prepare("SELECT m.*, u.nombre_completo
        FROM mensajes m
        JOIN usuarios u ON m.emisor_id=u.id
        WHERE m.conversacion_id = ?
        ORDER BY m.fecha ASC");
    $stmt->execute([$_GET['conv_id']]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($action === 'enviar') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO mensajes (conversacion_id, emisor_id, receptor_id, mensaje)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$data['conv_id'], $data['emisor_id'], $data['receptor_id'], $data['mensaje']]);
    echo json_encode(['success'=>true]);
}
