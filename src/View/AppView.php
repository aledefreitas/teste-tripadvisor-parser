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
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

use ZLX\Cache\Cache;

/**
 * Application View
 */
class AppView extends View
{
    /**
     * Retorna o caminho dos arquivos de assets de acordo com o manifest gerado pelo Gulp
     *
     * @param   string          $path
     *
     * @return string
     */
    public function render_asset($path)
    {
        $assets = $this->getAssetsFromManifest();

        return array_key_exists($path, $assets) === true ? $assets[$path] : '';
    }

    /**
     * Retorna um array contendo os caminhos do Rev gerado pelo Gulp para os arquivos de assets
     *
     * @return array
     */
    private function getAssetsFromManifest()
    {
        //return Cache::remember('Assets_from_manifest', function() {
            $manifest_path = WWW_ROOT . 'rev-manifest.json';

            if(!file_exists($manifest_path)) return [];

            try {
                $manifest_data = json_decode(file_get_contents($manifest_path), true);

                if(json_last_error() !== JSON_ERROR_NONE)
                    throw new \Exception('JSON Inválido');

                return $manifest_data;
            } catch(\Exception $e) {
                return [];
            }
    //    }, 'assets');
    }
}
