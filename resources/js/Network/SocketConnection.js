/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

var TokenParser = require("../Helper/TokenParser.js");
var Result = require('../UI/Result.js');
var Form = require('../UI/Form.js');

/**
 * Classe do SocketConnection
 *
 * @return {void}
 */
function SocketConnection() {
    this.accessToken = TokenParser.get();
    this.server_url = window.WS_URL;
    this.Connection = connect.call(this, null);
};

/**
 * Método privado que conecta ao WebSocket
 *
 * @return {Promise}
 */
var connect = function() {
    return new Promise(function(resolve, reject) {
        let ws = new WebSocket(this.server_url, this.accessToken);

        ws.onmessage = function(message) {
            _onMessageReceived(message);
        }.bind(this);

        ws.onopen = function() {
            resolve(ws);
        };

        ws.onerror = function(err) {
            reject(err);
        };
    }.bind(this))
};

/**
 * Retorna a promise de conexão do WebSocket
 *
 * @return {Promise}
 */
SocketConnection.prototype.connect = function() {
    return this.Connection.then(function(Socket) {
        return Socket;
    });
};

/**
 * Lida com as mensagens recebidas do WebSocket
 *
 * @param   {string}    message
 *
 * @return {void}
 */
var _onMessageReceived = function(message) {
    try {
        var msg = JSON.parse(message.data);

        if(msg[0] == 'error')
            throw(msg[1]);

        Result.load(msg[1]);
    } catch(e) {
        Form.load();
        alert(e);
    }
};

module.exports = new SocketConnection();
