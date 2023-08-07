<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\ORM\TableRegistry;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoginWithPlinMiddleware
    implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $isAuthComp = $request->getParam('controller') === 'AuthController';
        $isLogin = $request->getParam('action') === 'login';

        if($isAuthComp and $isLogin) {
            $plin = $request->getData('plin');
            $request = $request->withData('id', $plin);
        }

        return $handler->handle($request);
    }
}
