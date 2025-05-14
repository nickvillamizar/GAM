<?php
require_once __DIR__ . '/../config/db.php';
$data = json_decode(file_get_contents('php://input'), true);

$stmt = $pdo->prepare("
  INSERT INTO mensajes (emisor_id, receptor_id, mensaje, conversacion_id)
  VALUES (?, ?, ?, ?)
");
$stmt->execute([
  $data['emisor_id'],
  $data['receptor_id'],
  $data['mensaje'],
  $data['conversacion_id']
]);

echo json_encode(['success' => true]);
