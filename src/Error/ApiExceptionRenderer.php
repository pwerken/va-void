<?php
namespace App\Error;

use Cake\Core\Configure;
use Cake\Error\Debugger;
use Crud\Error\ExceptionRenderer;

class ApiExceptionRenderer extends ExceptionRenderer
{

	protected function _outputMessage($template)
	{
		$error = $this->controller->viewVars['error'];

		$data = [];
		$data['class'] = 'Error';
		$data['code'] = $error->getCode() ?: 500;
		$data['url'] = '/'. $this->controller->request->url;
		$data['message'] = $error->getMessage();
		$data['errors'] = @$this->controller->viewVars['errors'];

		if(Configure::read('debug')) {
			$data['DEBUG']['trace'] = Debugger::formatTrace($error->getTrace()
					, ['format' => 'array', 'args' => false]);

			$queryLog = $this->_getQueryLog();
			if($queryLog) {
				$data['DEBUG']['queryLog'] = $queryLog;
			}
		}

		$jsonOptions = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT;
		if(Configure::read('debug'))
			$jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;

		$this->controller->response->type('json');
		$this->controller->response->body(json_encode($data, $jsonOptions));
		$this->controller->response->statusCode($data['code']);
		return $this->controller->response;
	}

}
