<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Error\ErrorLogger as CakeErrorLogger;
use Psr\Http\Message\ServerRequestInterface;

class ErrorLogger extends CakeErrorLogger
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

        $plin = $request->getAttribute('identity')?->getIdentifier();
        if ($plin) {
            $message .= "\nLogged in user: {$plin}";
        }

        $body = $request->getParsedBody();
        if ($body) {
            $message .= "\nRequest data: " . var_export($body, true);
        }

        return substr($message, 1);
    }
}
