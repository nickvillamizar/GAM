<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once(__DIR__ . '/../config/db.php');

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"] ?? null;
    $contraseña = $_POST["contraseña"] ?? null;

    if (!$correo || !$contraseña) {
        $error = "Error: Todos los campos son obligatorios.";
    } else {
        try {
            $query = "SELECT u.id, u.nombre_completo, u.contraseña, r.nombre AS rol 
                      FROM usuarios u
                      INNER JOIN usuario_roles ur ON u.id = ur.usuario_id
                      INNER JOIN roles r ON ur.rol_id = r.id
                      WHERE u.correo = :correo
                      LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && isset($usuario["contraseña"]) && password_verify($contraseña, $usuario["contraseña"])) {
                $_SESSION["usuario_id"]      = $usuario["id"];
                $_SESSION["nombre_completo"] = $usuario["nombre_completo"];
                $_SESSION["rol"]             = $usuario["rol"];

                // Redirigir según el tipo de usuario
                switch ($usuario["rol"]) {
                    case "Paciente":
                        header("Location: ../paciente.php");
                        break;
                    case "Profesional":
                        header("Location: ../views/profesional_dashboard.php");
                        break;
                    case "Familiar":
                        header("Location: ../views/familiar_dashboard.php");
                        break;
                    default:
                        header("Location: ../index.php");
                        break;
                }
                exit();
            } else {
                $error = "Correo o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error = "Error en el inicio de sesión: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión - Apuesta Por Ti</title>
  <style>
    /* Reset básico */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Estilos generales */
    body {
        font-family: 'Poppins', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        position: relative;
        padding: 20px;
    }

    /* Fondo de video */
    #video-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    /* Contenedor del formulario */
    .form-container {
        background: rgba(0, 0, 0, 0.85);
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        overflow-y: auto;
        max-height: 90vh;
    }

    /* Título del formulario */
    .form-title {
        text-align: center;
        margin-bottom: 20px;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 3px 3px 6px rgba(255, 255, 255, 0.5);
    }

    /* Filas del formulario */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 15px;
    }

    /* Grupos de formularios */
    .form-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    /* Labels */
    .form-group label {
        font-weight: 800;
        font-size: 18px;
        margin-bottom: 5px;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-shadow: 2px 2px 5px rgba(255, 255, 255, 0.4);
    }

    /* Inputs y select */
    .form-group input,
    .form-group select {
        padding: 12px;
        border: 3px solid #00c3ff;
        border-radius: 8px;
        font-size: 18px;
        background: #222;
        color: #fff;
        font-weight: 700;
        outline: none;
        transition: 0.3s ease-in-out;
        text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.3);
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #ff4d4d;
    }

    /* Botón de enviar */
    .btn {
        display: block;
        width: 100%;
        padding: 14px;
        margin-top: 20px;
        background: linear-gradient(45deg, #00c3ff, #ff4d4d);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 20px;
        font-weight: 800;
        cursor: pointer;
        text-align: center;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);
    }

    .btn:hover {
        background: linear-gradient(45deg, #ff4d4d, #00c3ff);
    }

    /* Sección extra de campos dinámicos */
    .extra-fields {
        margin-top: 20px;
    }

    /* Título de sección */
    .section-title {
        font-size: 22px;
        font-weight: 800;
        margin-top: 20px;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 3px 3px 6px rgba(255, 255, 255, 0.5);
    }

    /* Responsive */
    @media (min-width: 600px) {
        .form-group {
            width: 48%;
        }
    }
  </style>
</head>
<body>
<video id="video-background" autoplay muted loop>
    <source src="video/date_video.mp4" type="video/mp4">
    Tu navegador no soporta el video.
</video>

  <div class="form-container">
    <h1 class="form-title">Bienvenido a Apuesta Por Ti</h1>
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (!empty($error)) {
        echo '<div class="alert-error">' . $error . '</div>';
    }
    ?>
    <form action="" method="POST">
      <div class="form-row">
        <div class="form-group">
          <label for="correo">Correo:</label>
          <input type="email" name="correo" id="correo" required>
        </div>
        <div class="form-group">
          <label for="contraseña">Contraseña:</label>
          <input type="password" name="contraseña" id="contraseña" required>
        </div>
      </div>
      <button type="submit" class="btn">Iniciar Sesión</button>
    </form>
  </div>
</body>
</html>
