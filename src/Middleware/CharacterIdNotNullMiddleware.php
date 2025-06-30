<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CharacterIdNotNullMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $hasPlinChin = $request->getAttribute('PlinChin', false);
        $params = $request->getAttribute('params');
        $params['pass'] ??= [];

        if ($hasPlinChin && count($params['pass']) > 0 && is_null($params['pass'][0])) {
            throw new NotFoundException();
        }

        return $handler->handle($request);
    }
}
