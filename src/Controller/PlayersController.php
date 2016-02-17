<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Players Controller
 *
 * @property App\Model\Table\PlayersTable $Players
 */
class PlayersController extends AppController {

	public function initialize() {
		parent::initialize();

		$this->Crud->mapAction('index', 'Crud.Index');
		$this->Crud->mapAction('view',
			[ 'className' => 'Crud.View'
			, 'contain' => [ 'Characters' ]
			]);
	}

	public function login() {
		if(isset($this->request->data['id']))
			$this->request->data['id'] = (string)$this->request->data['id'];

		$user = null;
		if($this->request->is('put') || $this->request->is('post'))
			$user = $this->Auth->identify();
		if (!$user)
			throw new UnauthorizedException('Invalid username or password');

		$this->set(
			[ '_serialize' =>
				[ 'token' => JWT::encode(
					[ 'sub' => $user['id']
					, 'exp' =>  time() + 604800
					], Security::salt())
				, 'player' => '/api/players/'.$user['id']
				]
			]);
	}
}
