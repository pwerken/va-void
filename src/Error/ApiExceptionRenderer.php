<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Error\Renderer\WebExceptionRenderer;
use Exception;
use Psr\Http\Message\ResponseInterface;

use App\Error\Exception\ValidationException;
use App\Utility\Json;

class ApiExceptionRenderer
    extends WebExceptionRenderer
{
    public function configuration($error)
    {
        $this->controller->set('error', $error);
        $this->controller->set('errors', $error->getErrors());

        return $this->_outputMessage('error400');
    }

    public function Render(): ResponseInterface
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

        $data = [];
        $data['file'] = $exception->getFile();
        $data['line'] = $exception->getLine();

        $trace = [];
        $trace[] = $this->traceLine($data);
        foreach($exception->getTrace() as $line) {
            $trace[] = $this->traceLine($line);
        }

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
        if(Configure::read('debug'))
            $data['trace'] = $trace;

        $response = $response->withType('json');
        $response->getBody()->write(Json::encode($data));
        $this->controller->setResponse($response);
        return $response;
    }

    private function traceLine(array $data)
    {
        if (!isset($data['file'])) {
            $str = '[internal function]: ';
        } else {
            $str = $data['file'].'('.$data['line'].'): ';
        }
        if (isset($data['class']))
            $str .= $data['class'].$data['type'];
        if (isset($data['function']))
            $str .= $data['function'].'()';

        return $str;
    }
}
