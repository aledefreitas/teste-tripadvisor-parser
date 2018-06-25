<?php
/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="parser-container">
                <div class="parser-content">
                    <div id="search-container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="text-center">Entre a URL de um restaurante do TripAdvisor</h2>

                                <form id="search-form">
                                    <div class="form-group">
                                        <label for="search-input">
                                            URL TripAdvisor:
                                        </label>
                                        <input type="text" class="form-control" id="search-input" name="search" aria-describedby="search-help" placeholder="Insira a URL do TripAdvisor" required data-error="Por favor, preencha este campo." />
                                        <small id="search-help">Ex.: https://www.tripadvisor.com.br/Restaurant_Review-g303628-d9790455-Reviews-Yokohama_Restaurante-Sao_Jose_Do_Rio_Preto_State_of_Sao_Paulo.html </small>
                                    </div>

                                    <center><button class="btn btn-dark btn-lg" type="submit">Buscar</button></center>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="loading-container">
                        <span class="loader"></span>
                    </div>

                    <div id="results-container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="jumbotron">
                                    <h1 class="display-4" data-parser-title>

                                    </h1>

                                    <p class="lead" data-parser-address></p>

                                    <hr class="my-4" />

                                    <div id="carousel" class="carousel slide" data-ride="carousel">
                                      <div class="carousel-inner" data-parser-carousel>
                                        <div class="carousel-item">
                                          <img class="d-block w-100" src="#{imageUrl}">
                                        </div>
                                      </div>
                                      <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                      </a>
                                      <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                      </a>
                                    </div>

                                    <hr class="my-4" />

                                    <div class="infos">
                                        <ul class="infos-list">
                                            <li>
                                                <strong>Telefone:</strong>
                                                <span data-parser-telephone></span>
                                            </li>

                                            <li>
                                                <strong>Nota:</strong>
                                                <span data-parser-score></span>
                                            </li>

                                            <li>
                                                <strong>Avaliações:</strong>
                                                <span data-parser-ratings></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <hr class="my-4" />

                                    <p class="lead text-center">
                                        <a class="btn btn-dark btn-lg" href="#" role="button" data-parser-restart>Pesquisar outro restaurante</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
