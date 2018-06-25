/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

var WebSocket = require('ws');
var http = require('http');
var ConnectionPool = require('../DataStructures/ConnectionPool.js');
var Listener = require('../Network/Listener.js');
var Authentication = require('../Middleware/Authentication.js');

var SocketServer = function(PORT) {
    var self = this;

    let http_server = http.createServer((req, res) => {
        res.writeHead(200, { 'Content-Type': 'text/html' });
        res.end('<h1>Teste EPICS - SOCKET SERVER</h1>');
    });

    http_server.listen(PORT);

    this.Server = new WebSocket.Server({
        'server': http_server,
        'verifyClient': Authentication
    });

    console.log("Servidor do Teste EPICS aberto na porta " + PORT);

    this.Server.on("connection", function(socket, upgradeReq) {
        var session_id = upgradeReq.headers['sec-websocket-protocol'];

        ConnectionPool.add(session_id, socket);

        socket.on('close', function() {
            ConnectionPool.delete(session_id);
        });
    });
};

module.exports = SocketServer;
