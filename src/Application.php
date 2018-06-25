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
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

use ZLX\Cache\Cache;
use ZLX\Session\Session;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap()
    {
        parent::bootstrap();

     	Cache::init([
            'prefix' => 'tripadvisor_parser',
			'instances' => [
				'default' => [
                    'engine' => 'memcached',
					'duration' => '+1 hour',
					'groups' => [ 'Parses', 'Session' ],
					'prevent_clear' => [ 'Session' ]
                ],
                'assets' => [
                    'engine' => 'memcached',
                    'duration' => '+5 minutes'
                ]
		   ]
    	]);

        if(php_sapi_name() !== "cli")
            Session::init([
                'cache_instance' => 'default',
                'cookie_name' => 'Parser_sess',
                'session_scret' => 'wxLl88ISVTz7lvHgZvOSKrxOWI7MjLA1',
                'security_salt' => 'bhY4dZ5bEeru9e1XlObtY9Mc95cfDcrQ'
            ]);
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(ErrorHandlerMiddleware::class)

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(AssetMiddleware::class)

            // Add routing middleware.
            // Routes collection cache enabled by default, to disable route caching
            // pass null as cacheConfig, example: `new RoutingMiddleware($this)`
            // you might want to disable this cache in case your routing is extremely simple
            ->add(new RoutingMiddleware($this, '_cake_routes_'));

        return $middlewareQueue;
    }
}
