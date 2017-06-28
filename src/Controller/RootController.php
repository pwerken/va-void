<?php
namespace App\Controller;

use App\Error\Exception\ConfigurationException;
use App\Utility\CheckConfig;
use Cake\Core\Configure;

class RootController
	extends AppController
{
	public static $jsonResponse = false;

	public function initialize()
	{
		parent::initialize();

		$this->Auth->allow(['root']);
	}

	public function root()
	{
		if(Configure::read('debug')) {
			$errors = [];
			foreach(CheckConfig::installation() as $msg => $ok) {
				if(!$ok) {
					$errors[] = $msg;
				}
			}
			if(!empty($errors)) {
				throw new ConfigurationException($errors);
			}
		}

		$this->set('user', $user = $this->Auth->user());
		$this->render('root');
	}

	public function cors()
	{
	}
}
