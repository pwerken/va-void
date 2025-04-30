<?php
declare(strict_types=1);

namespace App\Error;

use App\Error\Exception\ValidationException;
use App\Utility\Json;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Error\Renderer\WebExceptionRenderer;
use Psr\Http\Message\ResponseInterface;

class ApiExceptionRenderer extends WebExceptionRenderer
{
    public function render(): ResponseInterface
    {
        $exception = $this->error;
        $code = $this->getHttpCode($exception);

        if ($exception instanceof RecordNotFoundException) {
            $code = 404;
        }
        if (!is_numeric($code) || $code < 100 || $code > 599) {
            $code = 500;
        }

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
        $data['message'] = $exception->getMessage();
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

    /**
     * Convert stacktrace line info into an actual line/string.
     */
    private function traceLine(array $data): string
    {
        if (!isset($data['file'])) {
            $str = '[internal function]: ';
        } else {
            $str = $data['file'] . '(' . $data['line'] . '): ';
        }
        if (isset($data['class'])) {
            $str .= $data['class'] . $data['type'];
        }
        if (isset($data['function'])) {
            $str .= $data['function'] . '()';
        }

        return $str;
    }
}
