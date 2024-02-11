<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Error\ErrorLogger;
use Psr\Http\Message\ServerRequestInterface;

class AppErrorLogger extends ErrorLogger
{
    /**
     * Get the request context for an error/exception trace.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request to read from.
     * @return string
     */
    public function getRequestContext(ServerRequestInterface $request): string
    {
        $message = parent::getRequestContext($request);

        $body = $request->getParsedBody();
        if ($body) {
            $message .= "\nRequest data: " . print_r($body, true);
        }

        return $message;
    }
}
