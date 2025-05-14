<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro</title>
  <link rel="stylesheet" href="css/style_registro.css">
  <script>
    function mostrarCampos() {
      let rol = document.getElementById("rol_id").value;
      document.getElementById("paciente_fields").style.display = (rol == "1") ? "block" : "none";
      document.getElementById("profesional_fields").style.display = (rol == "2") ? "block" : "none";
      document.getElementById("familiar_fields").style.display = (rol == "3") ? "block" : "none";
    }
  </script>
</head>
<body>
  <video id="video-background" autoplay loop muted>
    <source src="video/date_video.mp4" type="video/mp4">
    Tu navegador no soporta videos en HTML5.
  </video>
  

  <div class="form-group" style="margin-top: 15px;">
        <button type="button" class="btn" onclick="window.location.href='index.php'">Volver al Inicio</button>
        <button type="button" class="btn" onclick="window.location.href='views/login.php'">Iniciar Sesión</button>
      </div>
  <div class="form-container">
    <h2 class="form-title">Registro de Usuario</h2>
    
    <form action="RegistroController.php" method="POST">
      <div class="form-group">
        <label>Nombre Completo:</label>
        <input type="text" name="nombre_completo" required>
      </div>

      <div class="form-group">
        <label>Cédula:</label>
        <input type="text" name="cedula" required>
      </div>

      <div class="form-group">
        <label>Correo:</label>
        <input type="email" name="correo" required>
      </div>

      <div class="form-group">
        <label>Celular:</label>
        <input type="text" name="celular" required>
      </div>

      <div class="form-group">
        <label>País:</label>
        <input type="text" name="pais" required>
      </div>

      <div class="form-group">
        <label>Ciudad:</label>
        <input type="text" name="ciudad" required>
      </div>

      <div class="form-group">
        <label>Dirección:</label>
        <input type="text" name="direccion">
      </div>

      <div class="form-group">
        <label>Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento">
      </div>

      <div class="form-group">
        <label>Género:</label>
        <select name="genero">
          <option value="Masculino">Masculino</option>
          <option value="Femenino">Femenino</option>
          <option value="Otro">Otro</option>
        </select>
      </div>

      <div class="form-group">
        <label>Contraseña:</label>
        <input type="password" name="contraseña" required>
      </div>

      <div class="form-group">
        <label>Rol:</label>
        <select name="rol_id" id="rol_id" required onchange="mostrarCampos()">
          <option value="">Selecciona un rol</option>
          <option value="1">Paciente</option>
          <option value="2">Profesional</option>
          <option value="3">Familiar</option>
        </select>
      </div>

      <!-- Campos de Paciente -->
      <div id="paciente_fields" style="display: none;">
        <h3>Datos del Paciente</h3>

        <div class="form-group">
          <label>Ocupación:</label>
          <input type="text" name="ocupacion">
        </div>

        <div class="form-group">
          <label>Estado Civil:</label>
          <select name="estado_civil">
            <option value="">Seleccione</option>
            <option value="Soltero">Soltero</option>
            <option value="Casado">Casado</option>
            <option value="Divorciado">Divorciado</option>
            <option value="Viudo">Viudo</option>
            <option value="Otro">Otro</option>
          </select>
        </div>

        <div class="form-group">
          <label>Contacto de Emergencia:</label>
          <input type="text" name="contacto_emergencia">
        </div>

        <div class="form-group">
          <label>Teléfono de Emergencia:</label>
          <input type="text" name="telefono_emergencia">
        </div>

        <div class="form-group">
          <label>Antecedentes Familiares:</label>
          <select name="antecedentes_familiares">
            <option value="">Seleccione</option>
            <option value="Ninguno">Ninguno</option>
            <option value="Ansiedad">Ansiedad</option>
            <option value="Depresión">Depresión</option>
            <option value="Hipertensión">Hipertensión</option>
            <option value="Diabetes">Diabetes</option>
            <option value="Otro">Otro</option>
          </select>
        </div>

        <div class="form-group">
          <label>Antecedentes Personales:</label>
          <select name="antecedentes_personales">
            <option value="">Seleccione</option>
            <option value="Ninguno">Ninguno</option>
            <option value="Asma">Asma</option>
            <option value="Obesidad">Obesidad</option>
            <option value="Problemas Cardiacos">Problemas Cardiacos</option>
            <option value="Otro">Otro</option>
          </select>
        </div>

        <div class="form-group">
          <label>Antecedentes Psiquiátricos:</label>
          <select name="antecedentes_psiquiatricos">
            <option value="">Seleccione</option>
            <option value="Ninguno">Ninguno</option>
            <option value="Ansiedad">Ansiedad</option>
            <option value="Depresión">Depresión</option>
            <option value="Trastorno Bipolar">Trastorno Bipolar</option>
            <option value="Otro">Otro</option>
          </select>
        </div>

        <div class="form-group">
          <label>Estado Actual:</label>
          <select name="estado_actual">
            <option value="">Seleccione</option>
            <option value="Estable">Estable</option>
            <option value="Inestable">Inestable</option>
            <option value="En Crisis">En Crisis</option>
          </select>
        </div>

        <div class="form-group">
          <label>Riesgos:</label>
          <select name="riesgos">
            <option value="">Seleccione</option>
            <option value="Bajo">Bajo</option>
            <option value="Medio">Medio</option>
            <option value="Alto">Alto</option>
          </select>
        </div>

        <div class="form-group">
          <label>Motivo de Consulta:</label>
          <select name="motivo_consulta" >
            <option value="">Seleccione</option>
            <option value="Ansiedad">Ansiedad</option>
            <option value="Depresión">Depresión</option>
            <option value="Estrés">Estrés</option>
            <option value="Problemas Familiares">Problemas Familiares</option>
            <option value="Otro">Otro</option>
          </select>
        </div>
      </div>

      <div id="profesional_fields" style="display: none;">
  <h3>Datos del Profesional</h3>
  <div class="form-group">
    <label>Número de Tarjeta Profesional:</label>
    <input type="number" name="numero_tarjeta_profesional" min="50000" max="80000" required>
  </div>
  <div class="form-group">
    <label>Especialidad:</label>
    <input type="text" name="especialidad" required>
  </div>
  <div class="form-group">
    <label>Años de Experiencia:</label>
    <input type="number" name="anios_experiencia" min="0" max="80" required>
  </div>
</div>


      <!-- Familiar -->
      <div id="familiar_fields" style="display: none;">
        <h3>Datos del Familiar</h3>
        <div class="form-group">
          <label>Paciente Asociado (ID):</label>
          <input type="text" name="paciente_id">
        </div>
        <div class="form-group">
          <label>Parentesco:</label>
          <input type="text" name="parentesco">
        </div>
      </div>

      <button type="submit" class="btn">Registrar</button>
      
    </form>
  </div>
</body>
</html>
