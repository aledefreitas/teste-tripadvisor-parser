/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

var Redis = require("redis").createClient();
var ConnectionPool = require('../DataStructures/ConnectionPool.js');

/**
 * Classe que escuta aos eventos do Redis enviados pelo PHP
 *
 * @return {void}
 */
function Listener() {
    Redis.on('message', function(channel, message) {
        var msg = JSON.parse(message);

        switch(msg.event) {
            case 'data':
            case 'error':
                let User = ConnectionPool.get(msg.session);

                if(User) {
                    User.send(JSON.stringify([msg.event, msg.data]));
                }

                break;

            default:
                break;
        }
    });

    Redis.subscribe('Parser.event');
};

module.exports = new Listener();
