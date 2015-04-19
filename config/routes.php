<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\Router;
use Cake\Utility\Inflector;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('Route');

Router::scope('/', function ($routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $routes->scope('/api', function ($routes) {

function getKeys($controller) {
	switch($controller) {
	case 'Players':     return [ 'plin' ];
	case 'Characters':  return [ 'plin', 'chin' ];
	case 'Items':       return [ 'itin' ];
	case 'Conditions':  return [ 'coin' ];
	case 'Powers':      return [ 'poin' ];
	default:            return [ 'id' ];
	}
}
function rest($routes, $name, $subs = [], $rels = []) {
	$lcName = Inflector::underscore($name);

	$routeOptions = [];
	foreach(getKeys($name) as $key) {
		$routeOptions[$key] = '[0-9]+';
		$routeOptions['pass'][] = $key;
	}
	$path = ':'.implode('/:', $routeOptions['pass']);

	$map =  [ 'index'   => [ '_method' => [ 'GET' ]         , 'path' => '' ]
			, 'add'     => [ '_method' => [ 'PUT' ]         , 'path' => '' ]
			, 'view'    => [ '_method' => [ 'GET' ]         , 'path' => $path ]
			, 'edit'    => [ '_method' => [ 'PUT', 'PATCH' ], 'path' => $path ]
			, 'delete'  => [ '_method' => [ 'DELETE' ]      , 'path' => $path ]
			];

	foreach($map as $method => $params) {
		$params['controller'] = $name;
		$params['action'] = $method;

		$url = '/'.$lcName.'/'.$params['path'];

		$routes->connect($url, $params, $routeOptions);
	}

	foreach($subs as $sub) {
		$sub = Inflector::underscore($sub);

		$params = [];
		$params['_method'] = 'GET';
		$params['controller'] = $name;
		$params['action'] = $sub.'Index';

		$routes->connect($url . '/' . $sub, $params, $routeOptions);
	}

	foreach($rels as $rel) {
		$lcRel = Inflector::underscore($rel);

		$routeOptions2 = $routeOptions;
		$path = [];
		foreach(getKeys($rel) as $key) {
			$routeOptions2[$key] = '[0-9]+';
			$routeOptions2['pass'][] = $key;
			$path[] = $key;
		}
		$path = ':'.implode('/:', $path);

		$map =  [ 'index'   => [ '_method' => [ 'GET' ]         , 'path' => '' ]
				, 'add'     => [ '_method' => [ 'PUT' ]         , 'path' => '' ]
				, 'view'    => [ '_method' => [ 'GET' ]         , 'path' => $path ]
				, 'edit'    => [ '_method' => [ 'PUT', 'PATCH' ], 'path' => $path ]
				, 'delete'  => [ '_method' => [ 'DELETE' ]      , 'path' => $path ]
				];

		foreach($map as $method => $params) {
			$params['controller'] = $name;
			$params['action'] = $lcRel.ucFirst($method);

			$urlRel = $url . '/' . $lcRel . '/' . $params['path'];

			$routes->connect($urlRel, $params, $routeOptions2);
		}
	}
}

        rest($routes, 'Players');
        rest($routes, 'Characters'
                        , [ 'Items']
                        , [ 'Conditions', 'Powers', 'Skills' ]
                        );
        rest($routes, 'Items');
        rest($routes, 'Conditions');
        rest($routes, 'Powers');
        rest($routes, 'Skills');

        rest($routes, 'Believes');
        rest($routes, 'Factions');
        rest($routes, 'Groups');
        rest($routes, 'Spells');
        rest($routes, 'Worlds');
    });

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `InflectedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'InflectedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'InflectedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('InflectedRoute');
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
