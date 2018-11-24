<?php
namespace App\Controller;

use Cake\Controller\Controller;

class RootController
	extends Controller
{
	public function initialize()
	{
		parent::initialize();
	}

	public function root()
	{
		return $this->redirect('/admin');
	}

	public function cors()
	{
	}
}
