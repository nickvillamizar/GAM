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
<!-- Sección Chat -->
<section>
  <h2>Mensajería en tiempo real</h2>
  <div id="chat-box" style="border:1px solid #ccc; height:200px; overflow-y:auto; padding:5px;">
    <div id="msgList"></div>
  </div>
  <textarea id="msgText" rows="2" style="width:100%;" placeholder="Escribe un mensaje..."></textarea>
  <button id="btnSend">Enviar</button>
</section>

<!-- Socket.io client -->
<!-- Socket.io client -->
<script src="https://cdn.jsdelivr.net/npm/socket.io-client@4/dist/socket.io.min.js"></script>


<script>
  // Estas variables las defines en PHP antes de este bloque:
  const conversacionId = <?= json_encode($conversacion_id ?? null) ?>;
  const emisorId       = <?= json_encode($_SESSION['usuario_id']) ?>;
  const receptorId     = <?= json_encode($profesional_usuario_id ?? null) ?>;

  // Conecta al servidor Socket.io
  const socket = io('ws://localhost:1489');

  // Cuando llegue un mensaje:
  socket.on('chat message', data => {
    // Solo procesar si es de esta conversación
    if (data.conversacion_id != conversacionId) return;

    const div = document.createElement('div');
    div.innerHTML = `<strong>${data.emisor_id == emisorId ? 'Tú' : 'Otro'}:</strong> ${data.mensaje}`;
    document.getElementById('msgList').appendChild(div);
    document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
  });

  // Función para enviar mensajes
  document.getElementById('btnSend').onclick = () => {
    const txt = document.getElementById('msgText');
    const mensaje = txt.value.trim();
    if (!mensaje) return;

    const payload = {
      conversacion_id: conversacionId,
      emisor_id: emisorId,
      receptor_id: receptorId,
      mensaje: mensaje
    };
    // 1) Emitir al servidor WebSocket
    socket.emit('chat message', payload);
    // 2) Guardar en base de datos vía AJAX
    fetch('chat/enviar_mensaje.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    txt.value = '';
  };

  // Al cargar la página, pide los mensajes existentes:
  window.addEventListener('load', () => {
    if (!conversacionId) return;
    fetch(`chat/cargar_mensajes.php?conv_id=${conversacionId}`)
      .then(res => res.json())
      .then(data => {
        const list = document.getElementById('msgList');
        data.forEach(m => {
          const div = document.createElement('div');
          div.innerHTML = `<strong>${m.emisor_id == emisorId ? 'Tú' : 'Otro'}:</strong> ${m.mensaje}`;
          list.appendChild(div);
        });
        document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
      });
  });
</script>

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
      <!-- Sección Chat -->


      
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



  </script>
</body>
</html>
