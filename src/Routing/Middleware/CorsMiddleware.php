<?php
declare(strict_types=1);

namespace App\Routing\Middleware;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CorsMiddleware
    implements MiddlewareInterface
{
    protected $config;

    public function __construct()
    {
        $default =
            [ 'allowOrigin' => [ '*' ]
            , 'allowCredentials' => true
            , 'allowMethods' => [ 'GET', 'PUT', 'DELETE', 'POST', 'OPTIONS' ]
            , 'allowHeaders' => [ 'X-Requested-With', 'Content-Type', 'Origin'
                                , 'Authorization', 'Accept' ]
            , 'exposeHeaders' => [ 'Location' ]
            ];

        $config = Configure::read('Cors', []);
        $this->config = Hash::merge($default, $config);
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        if(!method_exists($response, 'cors')) {
            return $response;
        }

        $cors = $response->cors($request);
        if($request->is('options')) {
            $cors = $cors
                ->allowMethods($this->config['allowMethods'])
                ->allowHeaders($this->config['allowHeaders'])
                ->exposeHeaders($this->config['exposeHeaders']);
        }
        return $cors
            ->allowOrigin($this->config['allowOrigin'])
            ->allowCredentials($this->config['allowCredentials'])
            ->build();
    }
}
