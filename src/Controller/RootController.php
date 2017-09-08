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
		$this->set('nomenu', true);
	}

	public function cors()
	{
	}
}
