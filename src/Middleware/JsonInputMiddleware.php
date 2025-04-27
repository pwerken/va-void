<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class JsonInputMiddleware
    implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        if ($request->is('put'))
            $request = $request->withHeader('Content-Type', 'application/json');

            # BodyParserMiddleware doesn't rewind before reading the stream
            $stream = $request->getBody();
            if($stream->isSeekable()) {
                $stream->rewind();
            }

        return $handler->handle($request);
    }
}
