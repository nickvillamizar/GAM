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
  <!-- Video de fondo -->
  <div class="video-background">
    <video id="bg-video" autoplay muted loop>
      <source src="video/date_video.mp4" type="video/mp4">
    </video>
  </div>

  <!-- Overlay y Panel -->
  <div class="overlay">
    <div class="panel">
      <h1>Bienvenido a su espacio , <?= htmlspecialchars($_SESSION['nombre_completo']) ?></h1>

      <!-- Sección Estado de Ánimo -->
      <section id="estado-animo">
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
      <section id="chat">
        <h2>Mensajería en tiempo real</h2>
        <div id="chat-box">
          <div id="msgList"></div>
        </div>
        <textarea id="msgText" rows="2" placeholder="Escribe un mensaje..."></textarea>
        <button id="btnSend">Enviar</button>
      </section>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/socket.io-client@4/dist/socket.io.min.js"></script>
  <script>
    // Variables de la conversación y usuario
    const conversacionId = <?= json_encode($conversacion_id ?? null) ?>;
    const emisorId = <?= json_encode($_SESSION['usuario_id']) ?>;
    const receptorId = <?= json_encode($profesional_usuario_id ?? null) ?>;
    const socket = io('ws://localhost:1489');

    // Función para cargar mensajes de la conversación
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

    // Escuchar nuevos mensajes
    socket.on('chat message', data => {
      if (data.conversacion_id != conversacionId) return;
      const div = document.createElement('div');
      div.innerHTML = `<strong>${data.emisor_id == emisorId ? 'Tú' : 'Otro'}:</strong> ${data.mensaje}`;
      document.getElementById('msgList').appendChild(div);
      document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
    });

    // Enviar mensajes
    document.getElementById('btnSend').onclick = () => {
      const txt = document.getElementById('msgText');
      const mensaje = txt.value.trim();
      if (!mensaje) return;
      const payload = { conversacion_id: conversacionId, emisor_id: emisorId, receptor_id: receptorId, mensaje: mensaje };
      socket.emit('chat message', payload);
      fetch('chat/enviar_mensaje.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      txt.value = '';
    };

    // Mostrar/Ocultar formulario de estado de ánimo
    document.getElementById('toggleMoodBtn').onclick = () => {
      const container = document.getElementById('moodFormContainer');
      container.style.display = (container.style.display === 'none') ? 'block' : 'none';
    };

    // Enviar estado de ánimo
    document.getElementById('formMood').onsubmit = async e => {
      e.preventDefault();
      const estado = document.querySelector('[name="estado"]').value;
      const comentario = document.querySelector('[name="comentario"]').value;
      if (!estado || !comentario) return alert("Por favor completa todos los campos.");

      const res = await fetch('api/mood.php?action=registrar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ paciente_id: emisorId, estado, comentario })
      });

      const json = await res.json();
      const msgEl = document.getElementById('mensajeBonito');
      msgEl.innerHTML = '';
      if (json.success) {
        msgEl.innerHTML = '<div class="alert-success">🌟 ¡Tu estado emocional ha sido registrado con éxito! 🌈</div>';
        e.target.reset();
        loadMood();
      } else {
        msgEl.innerHTML = `<div class="alert-error">❌ Error al registrar: ${json.error || 'Intenta de nuevo'}</div>`;
      }
      setTimeout(() => msgEl.innerHTML = '', 5000);
    };

    // Cargar lista de estados de ánimo
    async function loadMood() {
      let res = await fetch(`api/mood.php?action=listar&paciente_id=${emisorId}`);
      let data = await res.json();
      const ul = document.getElementById('moodList');
      ul.innerHTML = '';
      data.forEach(m => {
        ul.innerHTML += `<li>${m.fecha}: ${m.texto || m.estado} — ${m.comentario}</li>`;
      });
    }
    loadMood();
  </script>
  <div class="game-container">
  <h2>¡Diviértete un rato!</h2>
  <iframe
    src="https://playpager.com/embed/solitaire/"
    width="100%"
    height="600"
    style="border: 0; border-radius: 15px;"
    allowfullscreen
    loading="lazy">
  </iframe>
</div>

  <!-- Sección de Videollamada -->
<section id="videollamada">
  <h2>Citas Virtuales</h2>
  <div class="videollamada-container">
    <iframe src="https://meet.jit.si/MiSalaDeVideollamada" 
            width="100%" 
            height="600" 
         frameborder="0" 
            allow="camera; microphone; fullscreen; display-capture" 
            style="border-radius: 10px;">
    </iframe>
  </div>
</section>

</body>
</html>
