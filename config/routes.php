<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\RouteBuilder;
use Cake\Routing\Route\InflectedRoute;

return function (RouteBuilder $routes): void {

    $routes->setRouteClass(InflectedRoute::class);

    $defaults = [];
    $defaults['_method'] = 'GET';
    $defaults['controller'] = 'Root';
    $defaults['action'] = 'index';
    $routes->connect('/', $defaults);

    $defaults['_method'] = 'OPTIONS';
    $defaults['action'] = 'cors';
    $routes->connect('/*', $defaults);

    $routes->prefix('Admin', function (RouteBuilder $routes) {
        /**
         *  Admin pages
         */
        $defaults = [];
        $defaults['_method'] = ['GET', 'POST'];
        $defaults['controller'] = 'Root';
        $defaults['action'] = 'index';
        $routes->connect('/', $defaults);

        unset($defaults['controller']);
        $routes->connect('/{controller}', $defaults);

        unset($defaults['action']);
        $routes->connect('/{controller}/{action}/*', $defaults);
    });

    /**
     *  Authentication related URIs
     */
    $defaults = [];
    $defaults['_method'] = ['GET', 'PUT', 'POST'];
    $defaults['controller'] = 'Auth';
    $defaults['action'] = 'login';
    $routes->connect('/auth/login', $defaults);

    $defaults = [];
    $defaults['_method'] = 'GET';
    $defaults['controller'] = 'Auth';
    $defaults['action'] = 'social';
    $routes->connect('/auth/social', $defaults);

    $routeOptions = [];
    $routeOptions['pass'][] = 'provider';
    $routes->connect('/auth/social/{provider}', $defaults, $routeOptions);

    /**
     * Helper function for building the rest api
     */
    $rest = function ($routes, $name, $subs = [], $nest = [], $rels = []): void {
        $getKeys = function ($controller) {
            switch ($controller) {
                case 'Players':
                    return [ 'plin' ];
                case 'Characters':
                    return [ 'plin', 'chin' ];
                case 'Items':
                    return [ 'itin' ];
                case 'Conditions':
                    return [ 'coin' ];
                case 'Powers':
                    return [ 'poin' ];
                default:
                    return [ 'id' ];
            }
        };

        $lcName = strtolower($name);

        $routeOptions = [];
        foreach ($getKeys($name) as $key) {
            $routeOptions['pass'][] = $key;
            $routeOptions[$key] = '[0-9]+';
        }
        $path = '{' . implode('}/{', $routeOptions['pass']) . '}';
        $url = '/' . $lcName . '/' . $path;

        $map =
            [ 'index'   => [ '_method' => 'GET',    'path' => 0 ]
            , 'add'     => [ '_method' => 'PUT',    'path' => 0 ]
            , 'view'    => [ '_method' => 'GET',    'path' => 1 ]
            , 'edit'    => [ '_method' => 'PUT',    'path' => 1 ]
            , 'delete'  => [ '_method' => 'DELETE', 'path' => 1 ],
            ];

        foreach ($map as $method => $options) {
            // hacky special case #1
            if ($name == 'Characters' && $method == 'add') {
                continue;
            }

            $defaults['_method'] = $options['_method'];
            $defaults['controller'] = $name;
            $defaults['action'] = $method;

            if ($options['path'] == 0) {
                $routes->connect('/' . $lcName, $defaults, []);
            } else {
                $routes->connect($url, $defaults, $routeOptions);
            }

            // hacky special case #2
            if ($name == 'Characters' && $method == 'index') {
                $defaults['action'] = 'playersIndex';
                $routes->connect(
                    '/characters/{plin}',
                    $defaults,
                    ['pass' => ['plin'], 'plin' => '[0-9]+'],
                );
            }
        }

        if (
            $name == 'Characters'
            || $name == 'Items'
            || $name == 'Powers'
            || $name == 'Conditions'
        ) {
            $defaults['_method'] = 'POST';
            $defaults['controller'] = $name;
            $defaults['action'] = 'queue';
            $routes->connect($url . '/print', $defaults, $routeOptions);
        }

        foreach ($subs as $controller) {
            $defaults = [];
            $defaults['_method'] = 'GET';
            $defaults['controller'] = $controller;
            $defaults['action'] = $lcName . 'Index';

            $urlNest = $url . '/' . strtolower($controller);
            $routes->connect($urlNest, $defaults, $routeOptions);
        }

        foreach ($nest as $sub) {
            $controller = [$name, $sub];
            sort($controller);
            $controller = implode($controller);

            $defaults = [];
            $defaults['_method'] = 'GET';
            $defaults['controller'] = $controller;
            $defaults['action'] = $lcName . 'Index';

            $urlNest = $url . '/' . strtolower($sub);
            $routes->connect($urlNest, $defaults, $routeOptions);
        }

        foreach ($rels as $rel) {
            $routeOptions2 = $routeOptions;
            $path = [];
            foreach ($getKeys($rel) as $key) {
                $routeOptions2['pass'][] = $key;
                $routeOptions2[$key] = '[0-9]+';
                $path[] = $key;
            }
            $path = '{' . implode('}/{', $path) . '}';

            $map =
                [ 'index'   => [ '_method' => 'GET',    'path' => 0 ]
                , 'add'     => [ '_method' => 'PUT',    'path' => 0 ]
                , 'view'    => [ '_method' => 'GET',    'path' => 1 ]
                , 'edit'    => [ '_method' => 'PUT',    'path' => 1 ]
                , 'delete'  => [ '_method' => 'DELETE', 'path' => 1 ],
                ];

            $controller = [ $name, $rel ];
            sort($controller);
            $controller = implode($controller);
            $lcNest = strtolower($rel);

            foreach ($map as $method => $options) {
                $defaults = [];
                $defaults['_method'] = $options['_method'];
                $defaults['controller'] = $controller;
                $defaults['action'] = $lcName . ucfirst($method);

                $urlNest = $url . '/' . $lcNest;
                if ($options['path'] == 0) {
                    $routes->connect($urlNest, $defaults, $routeOptions);
                } else {
                    $urlNest .= '/' . $path;
                    $routes->connect($urlNest, $defaults, $routeOptions2);
                }
            }

            if ($controller == 'CharactersConditions' || $controller == 'CharactersPowers') {
                $defaults['_method'] = 'POST';
                $defaults['controller'] = $controller;
                $defaults['action'] = 'charactersQueue';
                $routes->connect($urlNest . '/print', $defaults, $routeOptions2);
            }
        }

    // hacky special case #3
        if ($name == 'Players') {
            $defaults = [];
            $defaults['_method'] = 'PUT';
            $defaults['controller'] = 'Characters';
            $defaults['action'] = 'add';
            $routeOptions = [ 'pass' => ['plin'], 'plin' => '[0-9]+' ];
            $routes->connect('/players/{plin}/characters', $defaults, $routeOptions);
        }
        if ($name == 'Characters') {
            $map = [
                'View'    => [ '_method' => 'GET',    'path' => 1 ],
                'Edit'    => [ '_method' => 'PUT',    'path' => 1 ],
                'Delete'  => [ '_method' => 'DELETE', 'path' => 1 ],
            ];

            $urlNest = $url . '/students';
            $defaults = [];
            $defaults['_method'] = 'GET';
            $defaults['controller'] = 'Teachings';
            $defaults['action'] = 'charactersIndex';
            $routes->connect($urlNest, $defaults, $routeOptions);

            $urlNest = $url . '/teacher';
            foreach ($map as $method => $options) {
                $defaults['_method'] = $options['_method'];
                $defaults['action'] = 'characters' . $method;
                $routes->connect($urlNest, $defaults, $routeOptions);
            }

            $defaults['_method'] = 'POST';
            $defaults['action'] = 'charactersQueue';
            $routes->connect($urlNest . '/print', $defaults, $routeOptions);
        }
    };

    /**
     * Rest API
     */
    $rest($routes, 'Players', ['Characters'], [], ['Socials']);
    $rest($routes, 'Characters', ['Items'], [], ['Conditions', 'Powers', 'Skills']);

    $rest($routes, 'Conditions', [], ['Characters']);
    $rest($routes, 'Powers', [], ['Characters']);
    $rest($routes, 'Skills', [], ['Characters']);

    $rest($routes, 'Items', [], [], []);

    $rest($routes, 'Factions', ['Characters']);

    $rest($routes, 'Manatypes');
    $rest($routes, 'Lammies');
    $rest($routes, 'Events');

    $defaults = [];
    $defaults['controller'] = 'Lammies';
    $actions = [
        ['queue', 'GET', '/lammies/queue'],
        ['printed', 'POST', '/lammies/printed'],
        ['pdfSingle', 'POST', '/lammies/single'],
        ['pdfDouble', 'POST', '/lammies/double'],
    ];

    foreach ($actions as [$action, $method, $url]) {
        $defaults['_method'] = $method;
        $defaults['action'] = $action;
        $routes->connect($url, $defaults, []);
    }

    $defaults = [];
    $defaults['_method'] = ['GET'];
    $defaults['controller'] = 'Believes';
    $defaults['action'] = 'index';
    $routes->connect('/believes', $defaults);

    $defaults = [];
    $defaults['_method'] = ['GET'];
    $defaults['controller'] = 'Groups';
    $defaults['action'] = 'index';
    $routes->connect('/groups', $defaults);

    $defaults = [];
    $defaults['_method'] = ['GET'];
    $defaults['controller'] = 'Worlds';
    $defaults['action'] = 'index';
    $routes->connect('/worlds', $defaults);
};
