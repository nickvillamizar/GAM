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

    <!-- 🎥 Video de fondo (corregido con CSS) -->
    <div class="video-container">
        <video autoplay muted loop class="video-fondo">
            <source src="../assets/videos/date_video.mp4" type="video/mp4">
            Tu navegador no soporta el video.
        </video>
    </div>

    <!-- 🔳 Capa oscura para mejorar visibilidad -->
    <div class="overlay"></div>

    <!-- 📌 Contenedor centrado -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box p-4 rounded">
            <h2 class="text-center">Iniciar Sesión</h2>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            <form method="POST" action="../controllers/AuthController.php">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-warning w-100">Ingresar</button>
            </form>
        </div>
    </div>

</body>
</html>
