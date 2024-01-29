<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Response;
use Cake\Error\ExceptionRenderer;
use Exception;

use App\Error\Exception\ValidationException;
use App\Utility\Json;

class ApiExceptionRenderer
    extends ExceptionRenderer
{
    public function configuration($error)
    {
        $this->controller->set('error', $error);
        $this->controller->set('errors', $error->getErrors());

        return $this->_outputMessage('error400');
    }

    protected function _outputMessage($template): Response
    {
        $error = $this->error;
        $code = $error->getCode();

        if ($error instanceof RecordNotFoundException) {
            $code = 404;
        }
        if (!is_numeric($code)
            || $code < Response::STATUS_CODE_MIN
            || $code > Response::STATUS_CODE_MAX) {
            $code = 500;
        }

        $response = $this->controller->getResponse();
        $response = $response->withStatus($code);

        $data = [];
        $data['file'] = $error->getFile();
        $data['line'] = $error->getLine();

        $trace = [];
        $trace[] = $this->traceLine($data);
        foreach($error->getTrace() as $line) {
            $trace[] = $this->traceLine($line);
        }

        $errors = [];
        if ($error instanceof ValidationException) {
            $errors = $error->getValidationErrors();
        }

        $data = [];
        $data['class'] = 'Error';
        $data['code'] = $code;
        $data['url'] = $this->controller->getRequest()->getRequestTarget();
        $data['message'] = $error->getMessage();
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
