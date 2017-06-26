<?php
namespace App\Error;

use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Network\Exception\HttpException;
use Cake\Network\Exception\InternalErrorException;
use Crud\Error\ExceptionRenderer;
use Exception;

class ApiExceptionRenderer extends ExceptionRenderer
{

	protected function _outputMessage($template)
	{
		$error = $this->controller->viewVars['error'];
		$status = $code = $error->getCode();
		try {
			$this->controller->response->statusCode($status);
		} catch(Exception $e) {
			$status = 500;
			$this->controller->response->statusCode($status);
		}

		$errors = [];
		if(isset($this->controller->viewVars['errors'])) {
			$errors = $this->controller->viewVars['errors'];
		}

		$data = [];
		$data['class'] = 'Error';
		$data['code'] = $code;
		$data['url'] = $this->controller->request->here();
		$data['message'] = $error->getMessage();
		$data['errors'] = $errors;

		$jsonOptions = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT;
		if(Configure::read('debug'))
			$jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;

		$this->controller->response->type('json');
		$this->controller->response->body(json_encode($data, $jsonOptions));
		return $this->controller->response;
	}
}
