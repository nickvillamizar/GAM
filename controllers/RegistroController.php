<?php
require_once '../config/db.php'; // Archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre_completo = trim($_POST['nombre_completo']);
    $cedula = trim($_POST['cedula']);
    $correo = trim($_POST['correo']);
    $celular = trim($_POST['celular']);
    $pais = trim($_POST['pais']);
    $ciudad = trim($_POST['ciudad']);
    $direccion = trim($_POST['direccion']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $genero = trim($_POST['genero']);
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT); // Encriptar contraseña
    $rol_id = intval($_POST['rol_id']);

    try {
        $pdo->beginTransaction();

        // Verificar si el correo o cédula ya están registrados
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ? OR cedula = ?");
        $stmt->execute([$correo, $cedula]);
        if ($stmt->fetch()) {
            throw new Exception("El correo o la cédula ya están registrados.");
        }

        // Insertar usuario
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, fecha_nacimiento, genero, contraseña) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre_completo, $cedula, $correo, $celular, $pais, $ciudad, $direccion, $fecha_nacimiento, $genero, $contraseña]);
        $usuario_id = $pdo->lastInsertId(); // Obtener el ID del usuario recién creado

        // Asignar rol en `usuario_roles`
        $stmt = $pdo->prepare("INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $rol_id]);

        // Insertar en la tabla específica según el rol
        if ($rol_id == 1) { // Paciente
            $stmt = $pdo->prepare("INSERT INTO pacientes (usuario_id, motivo_consulta) VALUES (?, 'Motivo no especificado')");
            $stmt->execute([$usuario_id]);
        } elseif ($rol_id == 2) { // Profesional
            $stmt = $pdo->prepare("INSERT INTO profesionales (usuario_id, especialidad, años_experiencia, pacientes_atendidos) 
                                   VALUES (?, 'No especificada', 0, 0)");
            $stmt->execute([$usuario_id]);
        } elseif ($rol_id == 3) { // Familiar
            $stmt = $pdo->prepare("INSERT INTO familiares (usuario_id, paciente_id, parentesco) VALUES (?, NULL, 'No especificado')");
            $stmt->execute([$usuario_id]);
        }

        $pdo->commit();
        header("Location: ../views/login.php?success=Registro exitoso. Ahora puedes iniciar sesión.");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: ../views/registro.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../views/registro.php");
    exit();
}
