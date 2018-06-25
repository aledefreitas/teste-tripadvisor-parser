/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

/**
 * Classe de data structure que faz um pool das conexões ativas
 *
 * @return {void}
 */
function ConnectionPool() {
    /**
     * Map contendo todas as conexões ativas
     *
     * @var {Map}
     */
    this.connections = new Map();
};

/**
 * Adiciona o socket da conexão do usuário identificado pelo session_id
 *
 * @param   {string}        session_id
 * @param   {socket}        socket
 *
 * @return {boolean}
 */
ConnectionPool.prototype.add = function(session_id, socket) {
    return this.connections.set(session_id, socket);
};

/**
 * Retorna o socket do usuário selecionado
 *
 * @param   {string}        session_id
 *
 * @return {boolean} | {WebSocket.Socket}
 */
ConnectionPool.prototype.get = function(session_id) {
    if(!session_id) return false;

    return this.connections.get(session_id);
};

/**
 * Deleta da Pool o socket de acordo com o session_id
 *
 * @param   {string}        session_id
 *
 * @return {boolean}
 */
ConnectionPool.prototype.delete = function(session_id) {
    return this.connections.delete(session_id);
};

module.exports = new ConnectionPool();
