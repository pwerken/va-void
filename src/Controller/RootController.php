<?php
namespace App\Controller;

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
		$this->set('user', $user = $this->Auth->user());
		$this->render('root');
	}

	public function cors()
	{
	}
}
