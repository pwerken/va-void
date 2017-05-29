<?php
namespace App\Controller;

use App\AuthState;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

class DebugController
	extends AppController
{

	public static $jsonResponse = false;

	public function initialize()
	{
		parent::initialize();

		if(Configure::read('debug') || AuthState::hasRole('Super'))
			$this->Auth->allow(['display']);
	}

	public function display(...$path)
	{
		$count = count($path);
		if (!$count) {
			$path = ['index'];
		}
		if (in_array('..', $path, true) || in_array('.', $path, true)) {
			throw new ForbiddenException();
		}
		$page = $subpage = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}

		$user = $this->Auth->user();
		AuthState::setAuth($this->Auth, $this->hasAuthUser());

		$this->set(compact('page', 'subpage', 'user'));

		try {
			$this->render(implode('/', $path));
		} catch (MissingTemplateException $exception) {
			if (Configure::read('debug')) {
				throw $exception;
			}
			throw new NotFoundException();
		}
	}

}
