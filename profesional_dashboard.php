<?php
session_start();
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
  <title>Panel Profesional</title>
  <link rel="stylesheet" href="css/profesional_dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/socket.io-client@4/dist/socket.io.min.js"></script>
</head>

<body>
  <div class="video-background">
    <video id="bg-video" autoplay muted loop>
      <source src="video/date_video.mp4" type="video/mp4">
    </video>
  </div>

  <div class="overlay">
    <div class="panel">
      <h1>Bienvenido Doctor@, <?= htmlspecialchars($_SESSION['nombre_completo']) ?></h1>

      <section>
        <h2>Estados de Ánimo del Paciente</h2>
        <label for="selectPaciente">Selecciona un paciente:</label>
        <select id="selectPaciente">
          <option value="">-- Selecciona --</option>
        </select>
        <ul id="estadosPaciente"></ul>
      </section>

      <section>
        <h2>Mensajería en tiempo real</h2>
        <div id="chat-box" style="border:1px solid #ccc; height:200px; overflow-y:auto; padding:5px;">
          <div id="msgList"></div>
        </div>
        <textarea id="msgText" rows="2" style="width:100%;" placeholder="Escribe un mensaje..."></textarea>
        <button id="btnSend">Enviar</button>
      </section>
    </div>
  </div>

  <script>
    const userId = <?= json_encode($_SESSION['usuario_id']) ?>;
    const conversacionId = <?= json_encode($conversacion_id ?? null) ?>;
    const emisorId = <?= json_encode($_SESSION['usuario_id']) ?>;
    const receptorId = <?= json_encode($profesional_usuario_id ?? null) ?>;

    function obtenerEmoji(valor) {
      switch (parseInt(valor)) {
        case 1: return "😢 Muy Triste";
        case 2: return "🙁 Triste";
        case 3: return "😐 Normal";
        case 4: return "🙂 Feliz";
        case 5: return "😄 Muy Feliz";
        default: return "🤔 Sin definir";
      }
    }

    async function cargarPacientes() {
      const res = await fetch('api/pacientes.php?action=listar');
      const pacientes = await res.json();
      const select = document.getElementById('selectPaciente');

      pacientes.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.usuario_id;
        opt.textContent = `${p.nombre_completo} (${p.usuario_id})`;
        select.appendChild(opt);
      });
    }

    document.getElementById('selectPaciente').addEventListener('change', async e => {
      const id = e.target.value;
      const ul = document.getElementById('estadosPaciente');
      ul.innerHTML = '';

      if (!id) return;

      const res = await fetch(`api/mood.php?action=listar&paciente_id=${id}`);
      const data = await res.json();

      if (data.length === 0) {
        ul.innerHTML = '<li>No hay registros aún.</li>';
        return;
      }

      data.forEach(m => {
        const li = document.createElement('li');
        li.innerHTML = `<strong>${m.fecha}</strong>: ${obtenerEmoji(m.estado)}<br><em>${m.comentario}</em>`;
        ul.appendChild(li);
      });
    });

    cargarPacientes();

    const socket = io('ws://localhost:1489');

    socket.on('chat message', data => {
      if (data.conversacion_id != conversacionId) return;

      const div = document.createElement('div');
      div.innerHTML = `<strong>${data.emisor_id == emisorId ? 'Tú' : 'Otro'}:</strong> ${data.mensaje}`;
      document.getElementById('msgList').appendChild(div);
      document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
    });

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

      socket.emit('chat message', payload);

      fetch('chat/enviar_mensaje.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      txt.value = '';
    };

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
