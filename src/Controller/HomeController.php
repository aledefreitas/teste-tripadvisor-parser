<?php
/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Http\Exception\ForbiddenException;

use \ZLX\Session\Session;
use \GearmanClient;

class HomeController extends AppController
{
    /**
     * Função para controlar as requisições à index da Home
     *
     * @return void
     */
    public function index()
    {
        $this->set("session_id", Session::getSessionId());
    }

    /**
     * Faz o parse dos dados do TripAdvisor e imprime os resultados em JSON
     *
     * @throws Cake\Http\Exception\ForbiddenException       Caso a request não seja post
     *
     * @return void
     */
    public function parse()
    {
        $this->viewBuilder()->viewClass = 'Json';

        if(!$this->request->is('post'))
            throw new ForbiddenException();

        try {
            if(!preg_match("/^http(s)?\:\/\/(www\.)?tripadvisor\.com\.br\/Restaurant\_Review(\-(g\d+))?\-(d\d+)\-([\w_-]+)\.html$/", $this->request->data['search']))
                throw new \Exception('URL Inválida. Por favor, insira uma URL válida de um restaurante no TripAdvisor');

            $GearmanClient = new GearmanClient();
            $GearmanClient->addServer('127.0.0.1');

            $GearmanClient->doBackground("TripAdvisorParser::parseUrl", serialize([
                'session' => Session::getSessionId(),
                'url' => $this->request->data['search']
            ]));

            if($GearmanClient->returnCode() != GEARMAN_SUCCESS)
                throw new \Exception('Não foi possível enviar a requisição para o servidor em background. Cheque se o Gearmand está instalado e rodando no servidor.');

            $message = [
                'success' => true
            ];
        } catch(\Exception $e) {
            $message = [
                'error' => $e->getMessage()
            ];
        }

        $this->set('message', $message);

        $this->set('_serialize', [
            'message'
        ]);
    }
}
