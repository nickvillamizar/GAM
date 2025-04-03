<?php
session_start();
require_once __DIR__ . '/../config/db.php'; // Asegúrate de que este archivo existe y tiene la conexión

// Si el usuario ya está logueado, redirigirlo al dashboard o página principal
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../dashboard.php");
    exit();
}

// Manejo del formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);

    if (!empty($correo) && !empty($contraseña)) {
        // Consulta para verificar las credenciales
        $sql = "SELECT id, nombre_completo, contraseña, rol_id FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $nombre_completo, $hash_password, $rol_id);
            $stmt->fetch();

            // Verificar la contraseña
            if (password_verify($contraseña, $hash_password)) {
                // Almacenar datos en la sesión
                $_SESSION['usuario_id'] = $id;
                $_SESSION['nombre_completo'] = $nombre_completo;
                $_SESSION['rol_id'] = $rol_id;

                // Redirigir según el rol
                switch ($rol_id) {
                    case 1:
                        header("Location: ../paciente_dashboard.php"); // Página del paciente
                        break;
                    case 2:
                        header("Location: ../profesional_dashboard.php"); // Página del profesional
                        break;
                    case 3:
                        header("Location: ../familiar_dashboard.php"); // Página del familiar
                        break;
                    default:
                        header("Location: ../dashboard.php"); // Página general
                        break;
                }
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Correo no registrado.";
        }
        $stmt->close();
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Apuesta por Ti</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>

    <!-- 🎥 Video de fondo -->
    <div class="video-container">
        <video autoplay muted loop class="video-fondo">
            <source src="../assets/date_video.mp4" type="video/mp4">
            Tu navegador no soporta el video.
        </video>
    </div>

    <!-- 🔳 Capa oscura -->
    <div class="overlay"></div>

    <!-- 📌 Formulario de Login -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box p-4 rounded">
            <h2 class="text-center">Iniciar Sesión</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-3">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                </div>
                <button type="submit" class="btn btn-warning w-100">Ingresar</button>
            </form>
            <p class="text-center mt-3">
                ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
            </p>
        </div>
    </div>

</body>
</html>
