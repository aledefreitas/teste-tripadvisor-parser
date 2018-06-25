/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */
var TokenParser = function() {
    let source = null;

    if(document.currentScript) {
        source = document.currentScript.src;
    } else {
        source = document._currentScript().src
    }

    this.token = source.match(new RegExp(/\?session\_id\=(\w+)$/i))[1];
};

/**
 * Retorna o token encontrado
 *
 * @return {string}
 */
TokenParser.prototype.get = function() {
    return this.token;
};

module.exports = new TokenParser();
