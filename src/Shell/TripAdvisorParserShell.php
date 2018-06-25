<?php
/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use Cake\Console\Shell;

class TripAdvisorParserShell extends Shell
{
	public $tasks = [
        "UrlParser"
    ];

	/**
	 * Método main da shell, é executado ao iniciar a Shell
	 *
	 * @return void
	 */
    public function main() {
        $worker = new \GearmanWorker();
        $worker->addServer("127.0.0.1");

		$worker->addFunction("TripAdvisorParser::parseUrl", [ $this->UrlParser, "parseUrl" ]);

		echo 	"========================================" . PHP_EOL;
		echo	"	TripAdvisorParser Iniciado" . PHP_EOL;
		echo 	"========================================" . PHP_EOL;
		echo 	PHP_EOL;
		echo	"Aguardando trabalhos..." . PHP_EOL . PHP_EOL;

		while($worker->work()):
			if (GEARMAN_SUCCESS != $worker->returnCode())
				echo "Houve um erro : " . $this->Worker->error() . PHP_EOL;
		endwhile;
    }
}
