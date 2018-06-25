/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */
 try {
     /**
      * Socket Server
      *
      * @var Server\SocketServer
      */
     var SocketServer = require("./src/Server/SocketServer.js");

     /**
      * Constante com a porta a ser utilizada pelo servidor
      *
      * @const int
      */
     const PORT = 3005;

     new SocketServer(PORT);
 } catch(e) {
     console.log("Exited with Fatal Error: " + e);
     console.log(e.stack);
 }
