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
use Cake\Routing\RouteBuilder;
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
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
#Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
	$defaults = [];
	$defaults['_method'] = 'GET';
	$defaults['controller'] = 'Pages';
	$defaults['action'] = 'display';
    $routes->connect('/help/*', $defaults);

	/**
	 * CORS catch all OPTIONS method
	 */
	$defaults = [];
	$defaults['_method'] = 'OPTIONS';
	$defaults['controller'] = 'App';
	$defaults['action'] = 'corsOptions';
	$routes->connect('/*', $defaults);

	/**
	 *	Authentication related URIs
	 */
	$defaults = [];
	$defaults['_method'] = ['GET', 'PUT', 'POST'];
	$defaults['controller'] = 'Auth';
	$defaults['action'] = 'login';
	$routes->connect('/auth/login', $defaults);

	$defaults = [];
	$defaults['_method'] = 'GET';
	$defaults['controller'] = 'Auth';
	$defaults['action'] = 'logout';
	$routes->connect('/auth/logout', $defaults);

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
function rest($routes, $name, $subs = [], $nest = [], $rels = []) {
	$lcName = strtolower($name);

	$routeOptions = [];
	foreach(getKeys($name) as $key) {
		$routeOptions['pass'][] = $key;
		$routeOptions[$key] = '[0-9]+';
	}
	$path = ':'.implode('/:', $routeOptions['pass']);
	$url = '/'.$lcName.'/'.$path;

	$map =  [ 'index'   => [ '_method' => 'GET',    'path' => 0 ]
			, 'add'     => [ '_method' => 'PUT',    'path' => 0 ]
			, 'view'    => [ '_method' => 'GET',    'path' => 1 ]
			, 'edit'    => [ '_method' => 'PUT',    'path' => 1 ]
			, 'delete'  => [ '_method' => 'DELETE', 'path' => 1 ]
			];

	foreach($map as $method => $options) {
		// hacky special case #1
		if($name == 'Characters' && $method == 'add')
			continue;

		$defaults['_method'] = $options['_method'];
		$defaults['controller'] = $name;
		$defaults['action'] = $method;

		if($options['path'] == 0)
			$routes->connect('/'.$lcName, $defaults, []);
		else
			$routes->connect($url, $defaults, $routeOptions);

		// hacky special case #2
		if($name == 'Characters' && $method == 'index') {
			$defaults['action'] = 'playersIndex';
			$routes->connect('/characters/:plin', $defaults
							, ['pass' => ['plin'], 'plin' => '[0-9]+']);
		}
	}

	if($name == 'Characters' || $name == 'Items') {
		$defaults['_method'] = 'POST';
		$defaults['controller'] = $name;
		$defaults['action'] = 'queue';
		$routes->connect($url.'/print', $defaults, $routeOptions);
	}

	foreach($subs as $controller) {
		$defaults = [];
		$defaults['_method'] = 'GET';
		$defaults['controller'] = $controller;
		$defaults['action'] = $lcName.'Index';

		$urlNest = $url.'/'.strtolower($controller);
		$routes->connect($urlNest, $defaults, $routeOptions);
	}

	foreach($nest as $sub) {
		$controller = [$name, $sub];
		sort($controller);
		$controller = implode($controller);

		$defaults = [];
		$defaults['_method'] = 'GET';
		$defaults['controller'] = $controller;
		$defaults['action'] = $lcName.'Index';

		$urlNest = $url.'/'.strtolower($sub);
		$routes->connect($urlNest, $defaults, $routeOptions);
	}

	foreach($rels as $rel) {
		$routeOptions2 = $routeOptions;
		$path = [];
		foreach(getKeys($rel) as $key) {
			$routeOptions2['pass'][] = $key;
			$routeOptions2[$key] = '[0-9]+';
			$path[] = $key;
		}
		$path = ':'.implode('/:', $path);

		$map =  [ 'index'   => [ '_method' => 'GET',    'path' => 0 ]
				, 'add'     => [ '_method' => 'PUT',    'path' => 0 ]
				, 'view'    => [ '_method' => 'GET',    'path' => 1 ]
				, 'edit'    => [ '_method' => 'PUT',    'path' => 1 ]
				, 'delete'  => [ '_method' => 'DELETE', 'path' => 1 ]
				];

		$controller = [ $name, $rel ];
		sort($controller);
		$controller = implode($controller);
		$lcNest = strtolower($rel);

		foreach($map as $method => $options) {
			$defaults = [];
			$defaults['_method'] = $options['_method'];
			$defaults['controller'] = $controller;
			$defaults['action'] = $lcName.ucfirst($method);

			$urlNest = $url.'/'.$lcNest;
			if($options['path'] == 0) {
				$routes->connect($urlNest, $defaults, $routeOptions);
			} else {
				$urlNest .= '/'.$path;
				$routes->connect($urlNest, $defaults, $routeOptions2);
			}
		}

		if($controller == 'CharactersConditions' || $controller == 'CharactersPowers') {
			$defaults['_method'] = 'POST';
			$defaults['controller'] = $controller;
			$defaults['action'] = 'charactersQueue';
			$routes->connect($urlNest.'/print', $defaults, $routeOptions2);
		}
	}

	// hacky special case #3
	if($name == 'Players') {
		$defaults = [];
		$defaults['_method'] = 'PUT';
		$defaults['controller'] = 'Characters';
		$defaults['action'] = 'add';
		$routeOptions = [ 'pass' => ['plin'], 'plin' => '[0-9]+' ];
		$routes->connect('/players/:plin/characters', $defaults, $routeOptions);
	}
}

	rest($routes, 'Players',  [ 'Characters' ]);
	rest($routes, 'Characters'
					, [ 'Items' ]
					, [ ]
					, [ 'Conditions', 'Powers', 'Skills', 'Spells' ]
					);

	rest($routes, 'Conditions', [], [ 'Characters' ]);
	rest($routes, 'Powers',     [], [ 'Characters' ]);
	rest($routes, 'Skills',     [], [ 'Characters' ]);
	rest($routes, 'Spells',     [], [ 'Characters' ]);

	rest($routes, 'Items',      [], [              ], ['Attributes']);
	rest($routes, 'Attributes', [], [ 'Items'      ]);

	rest($routes, 'Believes', [ 'Characters' ]);
	rest($routes, 'Factions', [ 'Characters' ]);
	rest($routes, 'Groups',   [ 'Characters' ]);
	rest($routes, 'Worlds',   [ 'Characters' ]);

	rest($routes, 'Lammies');

	$defaults = [];
	$defaults['controller'] = 'Lammies';
	$actions =	[ [ 'queue'        , 'GET',  '/lammies/queue'   ]
				, [ 'printed'      , 'POST', '/lammies/printed' ]
				, [ 'pdfSingle'    , 'POST', '/lammies/single'  ]
				, [ 'pdfDouble'    , 'POST', '/lammies/double'  ]
				];
	foreach($actions as list($action, $method, $url)) {
		$defaults['_method'] = $method;
		$defaults['action'] = $action;
		$routes->connect($url, $defaults, []);
	}

	if(strcmp(substr($_SERVER['SERVER_SOFTWARE'],0,8), "lighttpd") == 0) {
		$defaults = [];
		$defaults['_method'] = 'GET';
		$defaults['controller'] = 'Pages';
		$defaults['action'] = 'lighttpd';
		$routes->connect('/va-void/', $defaults);
	}
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
