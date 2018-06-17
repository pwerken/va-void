<?php
namespace App\Error;

use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Http\Exception\HttpException;
use Cake\Http\Exception\InternalErrorException;
use Crud\Error\ExceptionRenderer;
use Exception;

class ApiExceptionRenderer extends ExceptionRenderer
{
	public function configuration($error)
	{
		$this->controller->set('error', $error);
		$this->controller->set('errors', $error->getErrors());

		return $this->_outputMessage('error400');
	}

	protected function _outputMessage($template)
	{
		$response = $this->controller->response;
		$error = $this->controller->viewVars['error'];
		$status = $code = $error->getCode();
		try {
			$response = $response->withStatus($status);
		} catch(Exception $e) {
			$status = 500;
			$response = $response->withStatus($status);
		}

		$errors = [];
		if(isset($this->controller->viewVars['errors'])) {
			$errors = $this->controller->viewVars['errors'];
		}

		$data = [];
		$data['class'] = 'Error';
		$data['code'] = $code;
		$data['url'] = $this->controller->request->getRequestTarget();
		$data['message'] = $error->getMessage();
		$data['errors'] = $errors;

		$jsonOptions = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT;
		if(Configure::read('debug'))
			$jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;

		$response = $response->withType('json');
		$response->getBody()->write(json_encode($data, $jsonOptions));
		$this->controller->response = $response;
		return $response;
	}
}
