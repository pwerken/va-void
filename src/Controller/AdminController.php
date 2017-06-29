<?php
namespace App\Controller;

use App\Model\Entity\Player;
use App\Utility\AuthState;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

class AdminController
	extends Controller
{

	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('Flash');
		$this->loadComponent('Auth',
			[ 'storage' => 'Session'
			, 'authenticate' =>
				[ 'Form' =>
					[ 'userModel' => 'Players'
					, 'fields' => [ 'username' => 'id' ]
					]
				, 'ADmad/JwtAuth.Jwt' =>
					[ 'userModel' => 'Players'
					, 'fields' => [ 'username' => 'id' ]
					, 'parameter' => 'token'
					, 'queryDatasource' => true
					, 'unauthenticatedException' => '\Cake\Network\Exception\ForbiddenException'
				]	]
			, 'unauthorizedRedirect' => false
			, 'checkAuthIn' => 'Controller.initialize'
			, 'loginAction' => '/admin/login'
			]);

		if(Configure::read('debug') || AuthState::hasRole('Super'))
		{
			$this->Auth->allow(['index', 'login', 'logout', 'routes']);
			if(AuthState::hasRole('Player')) {
				$this->Auth->allow(['auth', 'hash', 'role']);
			}
			if(AuthState::hasRole('Super')) {
				$this->Auth->allow(['passwd']);
			}
		}

		$this->set('links', $this->links());
	}

	public function index()
	{
	}

	public function auth()
	{
		$this->loadModel('Players');
		$query = $this->Players->find('list',
			[ 'valueField' => 'id'
			, 'groupField' => 'role'
			]);

		$this->set('perms', $query->toArray());
	}

	public function hash()
	{
		if(!empty($this->request->data('password'))) {
			$hasher = new DefaultPasswordHasher();
			$hash = $hasher->hash($this->request->data('password'));
			$this->Flash->success($hash);
		}
	}

	public function login()
	{
		if(!$this->request->is('post')) {
			$this->set('user', $this->Auth->user());
			return;
		}

		$this->request->data('id', (string)$this->request->data('id'));
		$user = $this->Auth->identify();
		if(!$user) {
			$this->Flash->error('Invalid username or password');
			return;
		}

		$this->Auth->setUser($user);
		$this->set('user', $user);
	}

	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}

	public function passwd()
	{
		if(!$this->request->is('post')) {
			return;
		}

		$plin = $this->request->data('plin');
		$pass = $this->request->data('password');

		AuthState::setAuth($this->Auth, $plin);
		$this->loadModel('Players');
		$player = $this->Players->findById($plin)->first();
		if(is_null($player)) {
			$this->Flash->error("Player#$plin not found");
			return;
		}

		$this->Players->patchEntity($player, ['password' => $pass]);
		$this->Players->save($player);

		$errors = $player->errors('password');
		if(!empty($errors)) {
			$this->Flash->error(reset($errors));
		} else {
			$this->Flash->success("Player#$plin password set");
		}
	}

	public function role()
	{
		$this->set('roles', Player::roleValues());

		if(!$this->request->is('post')) {
			return;
		}

		$plin = $this->request->data('plin');
		$role = $this->request->data('role');

		AuthState::setAuth($this->Auth, $plin);
		$this->loadModel('Players');
		$player = $this->Players->findById($plin)->first();
		if(is_null($player)) {
			$this->Flash->error("Player#$plin not found");
			return;
		}

		$this->Players->patchEntity($player, ['role' => $role]);
		$this->Players->save($player);

		$errors = $player->errors('role');
		if(!empty($errors)) {
			$this->Flash->error(reset($errors));
		} else {
			$this->Flash->success("Player#$plin role set to `$role`");
		}
	}

	public function routes()
	{
	}

	private function links()
	{
		return	[ '/admin/routes' => 'View Configured Routes'
				, '/admin/auth'   => 'View Authorisations'
				, '/admin/hash'   => 'Create DB Password Hash'
				, '/admin/login'  => 'Account Login / Logout'
				, '/admin/passwd' => 'Set Player Password'
				, '/admin/role'   => 'Set Authorisation'
				];
	}

}
