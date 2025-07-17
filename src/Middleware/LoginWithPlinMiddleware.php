<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Utility\Hash;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoginWithPlinMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getAttribute('params');
        $isAuthComp = $params['controller'] === 'Auth';
        $isLogin = $params['action'] === 'login';

        if ($isAuthComp && $isLogin) {
            /** @var array $body */
            $body = $request->getParsedBody();

            $id = Hash::get($body, 'id');
            $plin = Hash::get($body, 'plin', $id);

            $request = $request->withParsedBody(Hash::insert($body, 'id', $plin));
        }

        return $handler->handle($request);
    }
}
