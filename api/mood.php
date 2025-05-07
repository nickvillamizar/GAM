<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

// Función auxiliar para convertir número → texto/emoticono
function estadoTexto(int $valor): string {
    return match($valor) {
        5 => '😄 Muy bien',
        4 => '🙂 Bien',
        3 => '😐 Neutral',
        2 => '🙁 Mal',
        1 => '😢 Muy mal',
        default => 'Sin estado',
    };
}

$action = $_GET['action'] ?? null;

if (!$action) {
    http_response_code(400);
    echo json_encode(['error' => 'Acción no especificada']);
    exit;
}

if ($action === 'registrar') {
    // Leer el JSON enviado por fetch()
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar datos mínimos
    if (empty($data['paciente_id']) || empty($data['estado'])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos']);
        exit;
    }

    // Insertar en la tabla estados_animo
    $stmt = $pdo->prepare("
        INSERT INTO estados_animo (paciente_id, estado, comentario)
        VALUES (:paciente_id, :estado, :comentario)
    ");
    $stmt->execute([
        ':paciente_id' => $data['paciente_id'],
        ':estado'      => $data['estado'],
        ':comentario'  => $data['comentario'] ?? ''
    ]);

    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'listar') {
    // Validar query param
    $paciente_id = $_GET['paciente_id'] ?? null;
    if (!$paciente_id) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'paciente_id requerido']);
        exit;
    }

    // Traer los registros más recientes
    $stmt = $pdo->prepare("
        SELECT id, paciente_id, estado, comentario, fecha
        FROM estados_animo
        WHERE paciente_id = :paciente_id
        ORDER BY fecha DESC
    ");
    $stmt->execute([':paciente_id' => $paciente_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatear la salida
    $result = [];
    foreach ($rows as $row) {
        $result[] = [
            'id'         => (int)$row['id'],
            'estado'     => (int)$row['estado'],
            'texto'      => estadoTexto((int)$row['estado']),
            'comentario' => $row['comentario'],
            'fecha'      => $row['fecha']
        ];
    }

    echo json_encode($result);
    exit;
}

// Si llegamos aquí, acción no reconocida
http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Acción inválida']);
