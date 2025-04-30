<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Http\Middleware\BodyParserMiddleware as CakeBodyParserMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class BodyParserMiddleware extends CakeBodyParserMiddleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        # BodyParserMiddleware doesn't rewind before reading the stream
        $stream = $request->getBody();
        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        return parent::process($request, $handler);
    }
}
