<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

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
