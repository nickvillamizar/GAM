<?php session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: views/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Paciente</title>
  <link rel="stylesheet" href="css/paciente_dashboard.css">
</head>
<body>
  <div class="video-background">
    <video id="bg-video" autoplay muted loop>
      <source src="video/date_video.mp4" type="video/mp4">
    </video>
  </div>

  <div class="overlay">
    <div class="panel">
      <h1>Bienvenido, <?= htmlspecialchars($_SESSION['nombre_completo']) ?></h1>

      <!-- Estado de Ánimo -->
      <section>
        <h2>Estado de Ánimo</h2>
        <button id="toggleMoodBtn">Mostrar / Ocultar Registro</button>
        <div id="moodFormContainer" style="display: none;">
          <form id="formMood">
            <select name="estado" required>
              <option value="">Selecciona</option>
              <option value="5">😄 Muy bien</option>
              <option value="4">🙂 Bien</option>
              <option value="3">😐 Neutral</option>
              <option value="2">🙁 Mal</option>
              <option value="1">😢 Muy mal</option>
            </select>
            <textarea name="comentario" placeholder="Comentario..."></textarea>
            <button type="submit">Registrar</button>
          </form>
        </div>
        <div id="mensajeBonito"></div>
        <ul id="moodList"></ul>
      </section>

      <!-- Próximas citas -->
      <section>
        <h2>Próximas Citas</h2>
        <button id="btnCitas">Cargar Citas</button>
        <ul id="citasList"></ul>
      </section>

      <!-- Chat -->
      <section>
        <h2>Mensajería</h2>
        <button id="btnConv">Cargar Conversaciones</button>
        <ul id="convList"></ul>
        <div id="chatBox" style="display:none">
          <ul id="msgList"></ul>
          <textarea id="msgText"></textarea>
          <button id="btnSend">Enviar</button>
        </div>
      </section>
    </div>
  </div>

  <script>
    const userId = <?= json_encode($_SESSION['usuario_id']) ?>;

    // Mostrar/Ocultar formulario de estado de ánimo
    document.getElementById('toggleMoodBtn').onclick = () => {
      const container = document.getElementById('moodFormContainer');
      container.style.display = (container.style.display === 'none') ? 'block' : 'none';
    };

    // Enviar estado de ánimo
    document.getElementById('formMood').onsubmit = async e => {
      e.preventDefault();

      // Obtener los valores del formulario
      const estado = document.querySelector('[name="estado"]').value;
      const comentario = document.querySelector('[name="comentario"]').value;

      // Verificar si los valores están completos
      if (!estado || !comentario) {
        alert("Por favor completa todos los campos.");
        return;
      }

      // Enviar datos al backend
      const res = await fetch('api/mood.php?action=registrar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          paciente_id: userId,
          estado: estado,
          comentario: comentario
        })
      });

      const json = await res.json();
      const msgEl = document.getElementById('mensajeBonito');
      msgEl.innerHTML = '';

      if (json.success) {
        msgEl.innerHTML = '<div class="alert-success">🌟 ¡Tu estado emocional ha sido registrado con éxito! Gracias por compartir cómo te sientes. 🌈</div>';
        e.target.reset();
        loadMood();  // Recargar la lista de estados
      } else {
        msgEl.innerHTML = '<div class="alert-error">❌ Error al registrar: ' + (json.error || 'Intenta de nuevo') + '</div>';
      }

      setTimeout(() => msgEl.innerHTML = '', 5000);
    };

    // Cargar lista de estados
    async function loadMood() {
      let r = await fetch(`api/mood.php?action=listar&paciente_id=${userId}`);
      let data = await r.json();
      const ul = document.getElementById('moodList');
      ul.innerHTML = '';
      data.forEach(m => {
        ul.innerHTML += `<li>${m.fecha}: ${m.texto || m.estado} — ${m.comentario}</li>`;
      });
    }
    loadMood();

    // Cargar citas
    document.getElementById('btnCitas').onclick = async () => {
      let r = await fetch(`api/citas.php?action=listar&paciente_id=${userId}`);
      let data = await r.json();
      const ul = document.getElementById('citasList');
      ul.innerHTML = '';
      data.forEach(c => {
        ul.innerHTML += `<li>${c.fecha} - ${c.nombre_completo} (${c.modalidad})</li>`;
      });
    };

    // Cargar conversaciones
    document.getElementById('btnConv').onclick = async () => {
      let r = await fetch(`api/conversaciones.php?action=listar&user_id=${userId}`);
      let data = await r.json();
      const ul = document.getElementById('convList');
      ul.innerHTML = '';
      data.forEach(c => {
        ul.innerHTML += `<li><a href="#" onclick="openChat(${c.id},${c.otro_id})">${c.nombre_completo}</a></li>`;
      });
    };

    let currConv = 0, currTarget = 0;

    // Abrir una conversación
    async function openChat(convId, targetId) {
      currConv = convId;
      currTarget = targetId;
      document.getElementById('chatBox').style.display = 'block';
      loadMsgs();
    }

    // Cargar mensajes de la conversación
    async function loadMsgs() {
      let r = await fetch(`api/mensajes.php?action=listar&conv_id=${currConv}`);
      let data = await r.json();
      const ul = document.getElementById('msgList');
      ul.innerHTML = '';
      data.forEach(m => {
        ul.innerHTML += `<li><strong>${m.nombre_completo}:</strong> ${m.mensaje}</li>`;
      });
    }

    // Enviar un mensaje
    document.getElementById('btnSend').onclick = async () => {
      let text = document.getElementById('msgText').value;
      await fetch('api/mensajes.php?action=enviar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          conv_id: currConv, emisor_id: userId,
          receptor_id: currTarget, mensaje: text
        })
      });
      document.getElementById('msgText').value = '';
      loadMsgs();
    };
  </script>
</body>
</html>
