<?php
namespace App\Routing\Middleware;

use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CorsMiddleware
{

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
        if($request->getHeader('Origin')) {
			$response->cors($request)
				->allowOrigin(['*.the-vortex.nl', 'yvo.muze.nl'])
				->allowCredentials(true)
				->allowMethods(['GET','PUT','DELETE','POST','OPTIONS'])
				->allowHeaders(['x-requested-with', 'Content-Type', 'origin'
						, 'authorization', 'accept', 'client-security-token'])
				->exposeHeaders(['Location'])
				->build();

            if($request->is('options')) {
                return $response;
            }
        }

        return $next($request, $response);
    }

}
