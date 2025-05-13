<?php
/*************************************************************************
 *  API  ·  registros de estado de ánimo
 *  Funciona con la BD que nos compartiste (pacientes ⇄ usuarios).
 *  1. Recibe el  ID del  usuario  (NO del paciente) desde el front-end.
 *  2. Lo convierte al  paciente_id  correcto.
 *  3. Inserta / lista usando  estados_animo.paciente_id .
 *************************************************************************/

require_once(__DIR__ . '/../config/db.php');   //  $pdo  (PDO)

header('Content-Type: application/json');
$action = $_GET['action'] ?? '';

// ----------  UTILIDAD: traducir usuario_id  →  paciente_id  ----------
function getPacienteId(PDO $pdo, int $usuario_id): ?int {
    $q = $pdo->prepare("SELECT id FROM pacientes WHERE usuario_id = ?");
    $q->execute([$usuario_id]);
    $row = $q->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['id'] : null;
}

/* =====================================================
   1. REGISTRAR  (POST  JSON)
      Front-end manda:
        {
          "paciente_id": <usuario_id>,   ← viene así desde paciente.php
          "estado":       1..5,
          "comentario":  "..."
        }
   ===================================================== */
if ($action === 'registrar') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    $usuario_id = $data['paciente_id'] ?? null;      // 👉 realmente es usuario_id
    $estado     = $data['estado']       ?? null;
    $comentario = $data['comentario']   ?? '';

    // validaciones mínimas
    if (!$usuario_id || !$estado || $estado < 1 || $estado > 5) {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos o inválidos']);
        exit;
    }

    // convertir a paciente_id
    $paciente_id = getPacienteId($pdo, (int)$usuario_id);
    if (!$paciente_id) {
        echo json_encode(['success' => false, 'error' => 'Paciente no encontrado']);
        exit;
    }

    // insertar
    try {
        $ins = $pdo->prepare(
            "INSERT INTO estados_animo (paciente_id, estado, comentario)
             VALUES (?, ?, ?)"
        );
        $ins->execute([$paciente_id, $estado, $comentario]);

        echo json_encode(['success' => true, 'message' => 'Estado de ánimo registrado']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

/* =====================================================
   2. LISTAR  (GET)
      Front-end llama:
        api/mood.php?action=listar&paciente_id=<usuario_id>
   ===================================================== */
if ($action === 'listar') {
    $usuario_id = $_GET['paciente_id'] ?? null;      // 👉 realmente es usuario_id

    if (!$usuario_id) {
        echo json_encode([]);
        exit;
    }

    $paciente_id = getPacienteId($pdo, (int)$usuario_id);
    if (!$paciente_id) {
        echo json_encode([]);
        exit;
    }

    $sel = $pdo->prepare(
        "SELECT fecha, estado, comentario
         FROM estados_animo
         WHERE paciente_id = ?
         ORDER BY fecha DESC"
    );
    $sel->execute([$paciente_id]);
    $rows = $sel->fetchAll(PDO::FETCH_ASSOC);

    // traducir número → texto con emoji
    $map = [1=>'😢 Muy mal',2=>'🙁 Mal',3=>'😐 Neutral',4=>'🙂 Bien',5=>'😄 Muy bien'];
    foreach ($rows as &$r) {
        $r['texto'] = $map[$r['estado']] ?? $r['estado'];
    }

    echo json_encode($rows);
    exit;
}

/* =====================================================
   Acción desconocida
   ===================================================== */
echo json_encode(['success' => false, 'error' => 'Acción no válida']);
exit;
