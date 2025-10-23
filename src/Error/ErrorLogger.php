<?php
declare(strict_types=1);

namespace App\Error;

use App\Error\Exception\ValidationException;
use Cake\Error\ErrorLogger as CakeErrorLogger;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

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

        return substr($message, 1);
    }

    /**
     * Generate the message for the exception
     *
     * @param \Throwable $exception The exception to log a message for.
     * @param bool $isPrevious False for original exception, true for previous
     * @param bool $includeTrace Whether to include a stack trace.
     * @return string Error message
     */
    protected function getMessage(Throwable $exception, bool $isPrevious = false, bool $includeTrace = false): string
    {
        $message = parent::getMessage($exception, $isPrevious, $includeTrace);
        if ($exception instanceof ValidationException) {
            $message .= "Validation Errors:\n";
            foreach ($exception->getValidationErrors() as $field => $errors) {
                foreach ($errors as $rule => $error) {
                    $message .= '- ' . $field . ': ' . $error . ' (' . $rule . ")\n";
                }
            }
        }

        return $message;
    }
}
