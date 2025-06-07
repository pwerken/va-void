<?php
declare(strict_types=1);

namespace App\Error;

use App\Error\Exception\ValidationException;
use App\Utility\Json;
use Authorization\Policy\Exception\MissingPolicyException;
use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Error\Renderer\WebExceptionRenderer;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiExceptionRenderer extends WebExceptionRenderer
{
    public function render(): ResponseInterface
    {
        $exception = $this->error;
        $code = $this->getHttpCode($exception);

        $response = $this->controller->getResponse();
        $response = $response->withStatus($code);

        $errors = [];
        if ($exception instanceof ValidationException) {
            $errors = $exception->getValidationErrors();
        }

        $data = [];
        $data['class'] = 'Error';
        $data['code'] = $code;
        $data['url'] = $this->controller->getRequest()->getRequestTarget();
        $data['message'] = $this->_message($exception, $code);
        $data['errors'] = $errors;
        if (Configure::read('debug')) {
            $first = [];
            $first['file'] = $exception->getFile();
            $first['line'] = $exception->getLine();

            $data['trace'] = [];
            $data['trace'][] = $this->traceLine($first);
            foreach ($exception->getTrace() as $line) {
                $data['trace'][] = $this->traceLine($line);
            }
        }

        $response = $response->withType('json');
        $response->getBody()->write(Json::encode($data));
        $this->controller->setResponse($response);

        return $response;
    }

    protected function getHttpCode(Throwable $exception): int
    {
        if ($exception instanceof MissingPolicyException) {
            return 404;
        }

        return parent::getHttpCode($exception);
    }

    protected function _message(Throwable $exception, int $code): string
    {
        if (!Configure::read('debug')) {
            if ($code < 500) {
                return 'Not Found';
            } else {
                return 'An Internal Error Has Occurred';
            }
        }

        return $exception->getMessage();
    }

    /**
     * Convert stacktrace line info into an actual line/string.
     */
    private function traceLine(array $data): string
    {
        if (!isset($data['file'])) {
            $str = '[internal function]';
        } else {
            $str = Debugger::trimPath($data['file']);
        }
        if (isset($data['line'])) {
            $str .= ':' . $data['line'];
        }

        return $str;
    }
}
