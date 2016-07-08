<?php
namespace App\Error;

use Cake\Core\Configure;
use Crud\Error\ExceptionRenderer;

class ApiExceptionRenderer extends ExceptionRenderer
{

	protected function _outputMessage($template)
	{
		$data = [];
		$data['class'] = 'Error';
		$data += $this->_getErrorData();
		$data['url'] = $this->controller->request->env('PATH_INFO');

		if(Configure::read('debug')) {
			$queryLog = $this->_getQueryLog();
			if($queryLog) {
				$data['queryLog'] = $queryLog;
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
