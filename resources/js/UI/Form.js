/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

var validator = require('bootstrap-validator');
var Loader = require('./Loader.js');

/**
 * Classe para a UI do Form
 *
 * @return {void}
 */
function Form() {
    this.bindEvents();
};

/**
 * Faz load da UI do Form
 *
 * @return {void}
 */
Form.prototype.load = function() {
    $("input[name=search]").val('');
    $("#search-container").show();
    $("#loading-container").hide();
    $("#results-container").hide();
};

/**
 * Ativa os eventos que vamos escutar nessa UI
 *
 * @return {void}
 */
Form.prototype.bindEvents = function() {
    /**
     * Validador do form
     */
    $("#search-form").validator().on('submit', function(e) {
        Loader.load();

        if(!e.isDefaultPrevented()) {
            var Form = $(this);

            $.ajax({
                url: './home/parse',
                method: 'POST',
                data: {
                    'search': $('input[name=search]').val()
                },
                dataType: 'json',
                success: function(response) {
                    if(response.message.error) {
                        alert(response.message.error);
                        this.load();
                    }
                }.bind(this),
                error: function() {
                    this.load();
                    alert("Houve um erro na comunicação com o servidor.");
                }.bind(this)
            });
        }

        e.preventDefault();
    }.bind(this))
};

module.exports = new Form();
