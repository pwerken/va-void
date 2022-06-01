<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Http\Response;
use Crud\Error\ExceptionRenderer;
use Exception;

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

		$response = $this->controller->getResponse();

        if ($code < Response::STATUS_CODE_MIN || $code > Response::STATUS_CODE_MAX) {
			$response = $response->withStatus(500);
		} else {
			$response = $response->withStatus($code);
		}

		$data = [];
		$data['file'] = $error->getFile();
		$data['line'] = $error->getLine();

		$errors = [];
		$errors[] = $this->traceLine($data);
		foreach($error->getTrace() as $trace) {
			$errors[] = $this->traceLine($trace);
		}

		$data = [];
		$data['class'] = 'Error';
		$data['code'] = $code;
		$data['url'] = $this->controller->getRequest()->getRequestTarget();
		$data['message'] = $error->getMessage();
		$data['errors'] = $errors;

		$jsonOptions = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT;
		if(Configure::read('debug'))
			$jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;

		$response = $response->withType('json');
		$response->getBody()->write(json_encode($data, $jsonOptions));
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
