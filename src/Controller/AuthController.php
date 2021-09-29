<?php
namespace App\Controller;

use App\Error\LoginFailedException;
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
		$user = $this->Auth->identify();
		if (!$user) {
			$id = (string)$this->request->getData('id');
			$this->request = $this->request->withData('id', $id);
			if($this->request->is('put') || $this->request->is('post'))
				$user = $this->Auth->identify();
		}
		if (!$user)
			throw new LoginFailedException('Invalid username or password');

		$this->set(
			[ '_serialize' =>
				[ 'class' => 'Auth'
				, 'token' => JWT::encode(
					[ 'sub' => $user['id']
					, 'exp' =>  time() + 60*60*24*7
					, 'name' => $user['full_name']
					, 'role' => $user['role']
					], Security::getSalt())
				, 'player' => '/players/'.$user['id']
				]
			]);
	}

	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}

}
