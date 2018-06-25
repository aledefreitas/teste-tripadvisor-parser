/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

var Form = require('./Form.js');

function Result() {
    this._imageItemHtml = $("div[data-parser-carousel]").html();
    this.bindEvents();
};


Result.prototype.load = function(data) {
    $('h1[data-parser-title]').html(data.title);
    $('p[data-parser-address]').html(data.address);
    $('span[data-parser-telephone]').html(data.telephone);
    $('span[data-parser-score]').html(data.score);
    $('span[data-parser-ratings]').html(data.ratings);

    $("div[data-parser-carousel]").html('');
    for(let i = 0; i < data.images.length; i++) {
        $("div[data-parser-carousel]").append(this._imageItemHtml.replace(/\#\{imageUrl\}/gi, data.images[i]));
    };

    var firstImage = $("div[data-parser-carousel]").find('.carousel-item').first();

    if(firstImage.length > 0)
        firstImage.addClass('active');

    $('.carousel').carousel();
    $("#search-container").hide();
    $("#loading-container").hide();
    $("#results-container").show();
};

Result.prototype.bindEvents = function() {
    $("a[data-parser-restart]").click(function(e) {
        e.preventDefault();

        Form.load();
    })
};

module.exports = new Result();
