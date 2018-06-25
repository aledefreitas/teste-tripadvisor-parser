<?php

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
namespace App\Shell\Task;

use Cake\Console\Shell;

use \GearmanJob;
use \Redis;
use \DOMDocument;
use \DOMXpath;

use ZLX\Cache\Cache;

/**
 * Classe responsável pela execução das funções em background do Parser
 */
class UrlParserTask extends Shell
{
    /**
     * Instancia do publisher do Redis
     *
     * @var \Redis
     */
    protected $publisher = NULL;

    /**
     * Método inicializador
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->publisher = new Redis();
        $this->publisher->pconnect("127.0.0.1", 6379);
        $this->publisher->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
    }

    /**
     * Função responsável por lidar com o Job do Worker para parses
     *
     * @param   \GearmanJob     $job            Payload do job
     *
     * @return boolean
     */
    public function parseUrl(GearmanJob $job)
    {
        $data = unserialize($job->workload());

        try {
            $url = $data['url'];
            $session = $data['session'];

            $retrieved_data = Cache::remember('Parser.'.$url, function() use ($url) {
                $pageContent = $this->getPage($url);

                if(!$pageContent)
                    throw new \Exception("Não foi possível acessar a página '".$url."'");

                return $this->parseData($pageContent);
            });

            if(!$retrieved_data)
                throw new \Exception("Restaurante não encontrado");

            $this->publisher->publish('Parser.event', json_encode([
                'event' => 'data',
                'session' => $session,
                'data' => $retrieved_data
            ]));

            return true;
        } catch(\Exception $e) {
            $this->publisher->publish('Parser.event', json_encode([
                'event' => 'error',
                'session' => $session,
                'data' => $e->getMessage()
            ]));

            return false;
        }
    }

    /**
     * Executa o cURL na url que foi enviada para ser parseada
     *
     * @param   string          $url
     *
     * @return string | boolean
     */
    private function getPage($url)
    {
        $ch = curl_init($url);

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($httpcode == 404)
            throw new \Exception("A página requisitada não foi encontrada no TripAdvisor (404)");

        return $result;
    }

    /**
     * Faz o parse dos dados adquiridos pelo HTML
     *
     * @param   string      $html
     *
     * @return array
     */
    private function parseData($html = '')
    {
        $DOM = new DOMDocument();
        @$DOM->loadHTML($html);

        return [
            'title' => $this->getRestaurantTitle($DOM),
            'address' => $this->getRestaurantAddress($DOM),
            'images' => $this->getRestaurantImages($DOM),
            'telephone' => $this->getRestaurantTelephone($DOM),
            'score' => $this->getRestaurantScore($DOM),
            'ratings' => $this->getRestaurantRatings($DOM)
        ];
    }

    /**
     * Retorna o nome do restaurante pesquisado
     *
     * @param   DOMDocument     $DOM
     *
     * @return string
     */
    private function getRestaurantTitle(DOMDocument $DOM)
    {
        return strip_tags($DOM->getElementById('HEADING')->nodeValue);
    }

    /**
     * Retorna o endereço do restaurante pesquisado
     *
     * @param   DOMDocument     $DOM
     *
     * @return string
     */
    private function getRestaurantAddress(DOMDocument $DOM)
    {
        $xpath = new DOMXpath($DOM);
        $script = $xpath->query( '//script[@type="application/ld+json"]' );

        $restaurantData = json_decode(trim($script->item(0)->nodeValue), true);

        return implode(", ", [
            $restaurantData['address']['streetAddress'],
            $restaurantData['address']['addressLocality'],
            $restaurantData['address']['addressRegion'],
            $restaurantData['address']['postalCode'],
        ]);
    }

    /**
     * Retorna as imagens do restaurante pesquisado
     *
     * @param   DOMDocument     $DOM
     *
     * @return array
     */
    private function getRestaurantImages(DOMDocument $DOM)
    {
        $final_images = [];

        $xpath = new DOMXpath($DOM);
        $expression = './/img[contains(concat(" ", normalize-space(@class), " "), " centeredImg ")]';

        foreach($xpath->evaluate($expression) as $image) {
            $imageSrc = $image->getAttribute('data-src');

            if(!$imageSrc)
                continue;

            $final_images[] = $imageSrc;
        }

        $final_images = array_unique($final_images);

        return $final_images;
    }

    /**
     * Retorna o telefone do restaurante pesquisado
     *
     * @param   DOMDocument     $DOM
     *
     * @return string
     */
    private function getRestaurantTelephone(DOMDocument $DOM)
    {
        $xpath = new DOMXpath($DOM);

        $expression = './/div[contains(concat(" ", normalize-space(@class), " "), " phone ")]';

        return strip_tags($xpath->evaluate($expression)->item(0)->nodeValue);
    }

    /**
     * Retorna a nota do restaurante pesquisado
     *
     * @param   DOMDocument     $DOM
     *
     * @return float<double>
     */
    private function getRestaurantScore(DOMDocument $DOM)
    {
        $xpath = new DOMXpath($DOM);
        $script = $xpath->query( '//script[@type="application/ld+json"]' );

        $restaurantData = json_decode(trim($script->item(0)->nodeValue), true);

        return $restaurantData['aggregateRating']['ratingValue'];
    }

    /**
     * Retorna o número de avaliações do restaurante pesquisado
     *
     * @param   DOMDocument     $DOM
     *
     * @return int
     */
    private function getRestaurantRatings(DOMDocument $DOM)
    {
        $xpath = new DOMXpath($DOM);
        $script = $xpath->query( '//script[@type="application/ld+json"]' );

        $restaurantData = json_decode(trim($script->item(0)->nodeValue), true);

        return $restaurantData['aggregateRating']['reviewCount'];
    }
}
