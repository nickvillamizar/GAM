<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../config/db.php'); // Asegúrate de que este archivo existe y contiene $pdo

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'listar') {
    try {
        $stmt = $pdo->query("
            SELECT 
                u.id AS usuario_id,
                u.nombre_completo,
                u.cedula,
                u.correo,
                u.celular,
                u.pais,
                u.ciudad,
                u.direccion,
                u.fecha_nacimiento,
                u.genero,
                p.id AS paciente_id,
                p.ocupacion,
                p.estado_civil,
                p.contacto_emergencia,
                p.telefono_emergencia,
                p.antecedentes_familiares,
                p.antecedentes_personales,
                p.antecedentes_psiquiatricos,
                p.estado_actual,
                p.riesgos,
                p.motivo_consulta
            FROM pacientes p
            INNER JOIN usuarios u ON p.usuario_id = u.id
        ");

        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($pacientes) {
            echo json_encode($pacientes);
        } else {
            echo json_encode([]);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener los pacientes: ' . $e->getMessage()]);
    }
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Acción no válida']);
