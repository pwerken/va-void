<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionAdminOnlyMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $isAdminComp = $request->getParam('controller') === 'Admin';
        if (!$isAdminComp) {
            $request->getSession()->destroy();
        }

        return $handler->handle($request);
    }
}
