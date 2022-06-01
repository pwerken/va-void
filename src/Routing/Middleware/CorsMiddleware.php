<?php
namespace App\Routing\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
	protected $config;

	public function __construct($config = [])
	{
		$default =
			[ 'allowOrigin' => [ '*' ]
			, 'allowCredentials' => true
			, 'allowMethods' => [ 'GET', 'PUT', 'DELETE', 'POST', 'OPTIONS' ]
			, 'allowHeaders' => [ 'X-Requested-With', 'Content-Type', 'Origin'
								, 'Authorization', 'Accept' ]
			, 'exposeHeaders' => [ 'Location' ]
			];

		$data = Configure::read('Cors', []);
		$this->config = array_merge($default, $data, $config);
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$cors = $handler->handle($request)->cors($request);
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
