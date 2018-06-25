/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

/**
 * Função utilizada para autenticar o handshake do websocket
 *
 * @see https://github.com/websockets/ws/blob/master/doc/ws.md
 *
 * @param   {Object}      handshake   Handshake info
 * @param   {Function}    accept      WebSocket
 */
var Authentication = function Authentication(handshake, accept) {
    try {
        if(handshake.req.headers['sec-websocket-protocol'].length !== 64)
            throw new Error("Chave de acesso inválida");

        // @TODO: Autenticar utilizando a id de sessão do usuário através do memcached

        return accept(true);
    } catch(e) {
        return accept(false, 401, e.message);
    }
};

module.exports = Authentication;
