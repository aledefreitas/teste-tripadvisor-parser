/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

/**
 * Classe do UI de Loader
 *
 * @return {void}
 */
function Loader() {

};

/**
 * Carrega o Loader
 *
 * @return {void}
 */
Loader.prototype.load = function() {
    $("#search-container").hide();
    $("#loading-container").show();
    $("#results-container").hide();
};

module.exports = new Loader();
