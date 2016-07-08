<?php
namespace App\Controller;

use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class AuthController
	extends AppController
{

	public function initialize()
	{
		parent::initialize();

		$this->Auth->allow(['login', 'logout']);
	}

	public function login()
	{
		$user = $this->Auth->user();
		if (!$user) {
			$this->request->data('id', (string)$this->request->data('id'));
			if($this->request->is('put') || $this->request->is('post'))
				$user = $this->Auth->identify();
		}
		if (!$user)
			throw new UnauthorizedException('Invalid username or password');

		$this->Auth->setUser($user);
		$this->set(
			[ '_serialize' =>
				[ 'class' => 'Auth'
				, 'token' => JWT::encode(
					[ 'sub' => $user['id']
					, 'exp' =>  time() + 604800
					], Security::salt())
				, 'player' => '/players/'.$user['id']
				]
			]);
	}

	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}

}
