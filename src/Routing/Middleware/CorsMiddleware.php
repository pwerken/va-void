<?php
namespace App\Routing\Middleware;

use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CorsMiddleware
{
	protected $config;

	public function __construct($config = [])
	{
		$default =
			[ 'allowOrigin' => [ '*' ]
			, 'allowCredentials' => true
			, 'allowMethods' => [ 'GET', 'PUT', 'DELETE', 'POST', 'OPTIONS' ]
			, 'allowHeaders' => [ 'x-requested-with', 'Content-type', 'origin'
								, 'authorization', 'accept' ]
			, 'exposeHeaders' => [ 'Location' ]
			];
		$this->config = array_merge($default, Configure::read('Cors'), $config);
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
		$response->cors($request)
			->allowOrigin($this->config['allowOrigin'])
			->allowCredentials($this->config['allowCredentials'])
			->allowMethods($this->config['allowMethods'])
			->allowHeaders($this->config['allowHeaders'])
			->exposeHeaders($this->config['exposeHeaders'])
			->build();

		if($request->is('options')) {
			return $response;
		}
		return $next($request, $response);
	}
}
