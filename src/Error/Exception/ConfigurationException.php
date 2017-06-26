<?php
namespace App\Error\Exception;

use Cake\Network\Exception\ServiceUnavailableException;

class ConfigurationException extends ServiceUnavailableException
{
	protected $errors = [];

	public function __construct($errors = [])
	{
		$this->errors = $errors;

		$count = count($errors);
		if($count > 1) {
			$message = sprintf('%d configuration error occurred', $count);
		} else {
			$message = 'A configuration error occurred';
		}

		parent::__construct($message);
	}

	public function getErrors()
	{
		return $this->errors;
	}
}
