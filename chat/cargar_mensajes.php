<?php
require_once __DIR__ . '/../config/db.php';
$conv_id = intval($_GET['conv_id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM mensajes WHERE conversacion_id = ? ORDER BY fecha ASC");
$stmt->execute([$conv_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
