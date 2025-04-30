<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apuesta Por Ti - Tu Lugar Seguro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Video de fondo -->
    <video autoplay muted loop id="bg-video">
        <source src="video/date_video.mp4" type="video/mp4">
        Tu navegador no soporta el video.
    </video>

    <div class="overlay">
        <header>
            <h1>Apuesta Por Ti</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="views/login.php">Iniciar sesión</a></li>
                    <li><a href="registro.php" class="btn">Registrarse</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="hero">
                <h2>Tu bienestar es nuestra prioridad</h2>
                <p>Encuentra apoyo, orientación y herramientas para tu recuperación en un entorno seguro y confiable.</p>
                <a class="btn" href="registro.php">Únete Ahora</a> <!-- Corregido -->
            </section>

            <section class="features">
                <h2>¿Qué ofrecemos?</h2>
                <div class="feature-list">
                    <div class="feature">
                        <h3>Autoevaluaciones</h3>
                        <p>Herramientas para evaluar y comprender tu situación.</p>
                    </div>
                    <div class="feature">
                        <h3>Profesionales Capacitados</h3>
                        <p>Acceso a expertos en salud mental para apoyarte.</p>
                    </div>
                    <div class="feature">
                        <h3>Foros y Comunidad</h3>
                        <p>Comparte experiencias y encuentra apoyo en otros usuarios.</p>
                    </div>
                </div>
            </section>

            <section class="testimonios">
                <h2>Lo que dicen nuestros usuarios</h2>
                <div class="testimonio">
                    <p>"Gracias a esta plataforma, encontré el apoyo que necesitaba. Hoy me siento más fuerte y acompañado en mi proceso."</p>
                    <span>- Juan Pérez</span>
                </div>
                <div class="testimonio">
                    <p>"Un espacio seguro donde puedo hablar sin miedo y recibir la ayuda que necesito."</p>
                    <span>- María Rodríguez</span>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; 2025 Apuesta Por Ti. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
