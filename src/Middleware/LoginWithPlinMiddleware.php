<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoginWithPlinMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $isAuthComp = $request->getParam('controller') === 'Auth';
        $isLogin = $request->getParam('action') === 'login';

        if ($isAuthComp && $isLogin) {
            $id = $request->getData('id');
            $plin = $request->getData('plin', $id);
            $request = $request->withData('id', $plin);
        }

        return $handler->handle($request);
    }
}
