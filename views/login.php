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
            $query = "SELECT u.id, u.contraseña, r.nombre AS rol 
                      FROM usuarios u
                      INNER JOIN usuario_roles ur ON u.id = ur.usuario_id
                      INNER JOIN roles r ON ur.rol_id = r.id
                      WHERE u.correo = :correo";
            $stmt = $pdo->prepare($query);
            $stmt->execute([":correo" => $correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($contraseña, $usuario["contraseña"])) {
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["rol"] = $usuario["rol"];

                // Redirigir según el tipo de usuario
                switch ($usuario["rol"]) {
                    case "Paciente":
                        header("Location: ../views/paciente_dashboard.php");
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
    body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
    .container { max-width: 500px; margin: 50px auto; }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }
    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }
    form { background: #fff; border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
    label { display: block; margin-top: 10px; }
    input[type="email"],
    input[type="password"] { width: 100%; padding: 8px; margin-top: 5px; }
    button { margin-top: 15px; padding: 10px; width: 100%; background-color: #007bff; border: none; color: white; border-radius: 5px; cursor: pointer; }
    button:hover { background-color: #0069d9; }
    h1 { text-align: center; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Bienvenido a Apuesta Por Ti</h1>
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
      <label for="correo">Correo:</label>
      <input type="email" name="correo" id="correo" required>
      <label for="contraseña">Contraseña:</label>
      <input type="password" name="contraseña" id="contraseña" required>
      <button type="submit">Iniciar Sesión</button>
    </form>
  </div>
</body>
</html>
