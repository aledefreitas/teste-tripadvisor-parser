/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

global.$ = global.jQuery = require('jquery');
var bootstrap = require('bootstrap');

var Form = require('./UI/Form.js');
var SocketConnection = require('./Network/SocketConnection.js');
var currentScript = require('currentscript');

(function() {
    'use strict';

    var Parser = function() {
        Form.load();
    };

    return new Parser();
})();
