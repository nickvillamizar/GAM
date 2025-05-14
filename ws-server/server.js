// ws-server/server.js
const io = require('socket.io')(1489, {
    cors: { origin: "*" }
  });
  
  io.on('connection', socket => {
    console.log('Cliente conectado:', socket.id);
  
    socket.on('chat message', data => {
      console.log('Mensaje recibido de', socket.id, '–', data);
      io.emit('chat message', data);
    });
  
    socket.on('disconnect', () => {
      console.log('Cliente desconectado:', socket.id);
    });
  });
  
  console.log('Servidor WebSocket corriendo en puerto 1489');
  