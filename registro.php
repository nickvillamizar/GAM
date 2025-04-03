<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Apuesta por Ti</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>

    <!-- 🎥 Video de fondo -->
    <div class="video-container">
        <video autoplay muted loop class="video-fondo">
            <source src="../assets/" type="video/mp4">
            Tu navegador no soporta el video.
        </video>
    </div>

    <!-- 🔳 Capa oscura -->
    <div class="overlay"></div>

    <!-- 📌 Formulario de registro -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box p-4 rounded">
            <h2 class="text-center">Registrarse</h2>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            <form method="POST" action="../controllers/RegistroController.php">
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                </div>
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-3">
                    <label for="celular" class="form-label">Celular</label>
                    <input type="text" class="form-control" id="celular" name="celular" required>
                </div>
                <div class="mb-3">
                    <label for="pais" class="form-label">País</label>
                    <input type="text" class="form-control" id="pais" name="pais" required>
                </div>
                <div class="mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion">
                </div>
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                </div>
                <div class="mb-3">
                    <label for="genero" class="form-label">Género</label>
                    <select class="form-control" id="genero" name="genero" required>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                </div>
                <div class="mb-3">
                    <label for="rol_id" class="form-label">Tipo de Usuario</label>
                    <select class="form-control" id="rol_id" name="rol_id" required>
                        <option value="1">Paciente</option>
                        <option value="2">Profesional</option>
                        <option value="3">Familiar</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning w-100">Registrarse</button>
            </form>
        </div>
    </div>

</body>
</html>
