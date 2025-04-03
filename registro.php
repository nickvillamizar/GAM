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
  <h2 style="text-align: center;">Registro de Usuario</h2>
  <form action="RegistroController.php" method="POST">
  <video id="video-background" autoplay loop muted>
    <source src="video/date_video.mp4" type="video/mp4">
    Tu navegador no soporta videos en HTML5.
  </video>
    <!-- Datos generales -->
    <label>Nombre Completo:</label>
    <input type="text" name="nombre_completo" required>

    <label>Cédula:</label>
    <input type="text" name="cedula" required>

    <label>Correo:</label>
    <input type="email" name="correo" required>

    <label>Celular:</label>
    <input type="text" name="celular" required>

    <label>País:</label>
    <input type="text" name="pais" required>

    <label>Ciudad:</label>
    <input type="text" name="ciudad" required>

    <label>Dirección:</label>
    <input type="text" name="direccion">

    <label>Fecha de Nacimiento:</label>
    <input type="date" name="fecha_nacimiento">

    <label>Género:</label>
    <select name="genero">
      <option value="Masculino">Masculino</option>
      <option value="Femenino">Femenino</option>
      <option value="Otro">Otro</option>
    </select>

    <label>Contraseña:</label>
    <input type="password" name="contraseña" required>

    <label>Rol:</label>
    <select name="rol_id" id="rol_id" required onchange="mostrarCampos()">
      <option value="">Selecciona un rol</option>
      <option value="1">Paciente</option>
      <option value="2">Profesional</option>
      <option value="3">Familiar</option>
    </select>

    <!-- Campos para Paciente -->
    <div id="paciente_fields" class="section" style="display: none;">
      <h3>Datos del Paciente</h3>
      <label>Ocupación:</label>
      <input type="text" name="ocupacion">
      
      <label>Estado Civil:</label>
      <select name="estado_civil">
        <option value="">Seleccione</option>
        <option value="Soltero">Soltero</option>
        <option value="Casado">Casado</option>
        <option value="Divorciado">Divorciado</option>
        <option value="Viudo">Viudo</option>
        <option value="Otro">Otro</option>
      </select>
      
      <label>Contacto de Emergencia:</label>
      <input type="text" name="contacto_emergencia">
      
      <label>Teléfono de Emergencia:</label>
      <input type="text" name="telefono_emergencia">
      
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
      
      <label>Antecedentes Personales:</label>
      <select name="antecedentes_personales">
        <option value="">Seleccione</option>
        <option value="Ninguno">Ninguno</option>
        <option value="Asma">Asma</option>
        <option value="Obesidad">Obesidad</option>
        <option value="Problemas Cardiacos">Problemas Cardiacos</option>
        <option value="Otro">Otro</option>
      </select>
      
      <label>Antecedentes Psiquiátricos:</label>
      <select name="antecedentes_psiquiatricos">
        <option value="">Seleccione</option>
        <option value="Ninguno">Ninguno</option>
        <option value="Ansiedad">Ansiedad</option>
        <option value="Depresión">Depresión</option>
        <option value="Trastorno Bipolar">Trastorno Bipolar</option>
        <option value="Otro">Otro</option>
      </select>
      
      <label>Estado Actual:</label>
      <select name="estado_actual">
        <option value="">Seleccione</option>
        <option value="Estable">Estable</option>
        <option value="Inestable">Inestable</option>
        <option value="En Crisis">En Crisis</option>
      </select>
      
      <label>Riesgos:</label>
      <select name="riesgos">
        <option value="">Seleccione</option>
        <option value="Bajo">Bajo</option>
        <option value="Medio">Medio</option>
        <option value="Alto">Alto</option>
      </select>
      
      <label>Motivo de Consulta:</label>
      <select name="motivo_consulta" required>
        <option value="">Seleccione</option>
        <option value="Ansiedad">Ansiedad</option>
        <option value="Depresión">Depresión</option>
        <option value="Estrés">Estrés</option>
        <option value="Problemas Familiares">Problemas Familiares</option>
        <option value="Otro">Otro</option>
      </select>
    </div>

    <!-- Campos para Profesional -->
    <div id="profesional_fields" class="section" style="display: none;">
      <h3>Datos del Profesional</h3>
      <label>Número de Tarjeta Profesional:</label>
      <input type="number" name="numero_tarjeta_profesional" min="50000" max="80000">
      
      <label>Especialidad:</label>
      <input type="text" name="especialidad">
      
      <label>Años de Experiencia:</label>
      <input type="number" name="años_experiencia" min="0">
    </div>

    <!-- Campos para Familiar -->
    <div id="familiar_fields" class="section" style="display: none;">
      <h3>Datos del Familiar</h3>
      <label>Paciente Asociado (ID):</label>
      <input type="text" name="paciente_id">
      
      <label>Parentesco:</label>
      <input type="text" name="parentesco">
    </div>

    <br>
    <button type="submit">Registrar</button>
  </form>
</body>
</html>