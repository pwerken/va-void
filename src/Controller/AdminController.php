<?php
namespace App\Controller;

use App\Model\Entity\Player;
use App\Shell\BackupShell;
use App\Utility\AuthState;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Migrations\Migrations;

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
			, 'loginAction' => '/admin'
			]);

		if(Configure::read('debug') || AuthState::hasRole('Super'))
		{
			$this->Auth->allow(['index', 'logout', 'checks', 'routes']);
			if(AuthState::hasRole('Player')) {
				$this->Auth->allow(['authorisation']);
			}
			if(AuthState::hasRole('Super')) {
				$this->Auth->allow(['authentication']);
			}
		}

		$this->viewBuilder()->setLayout('admin');
		$this->set('user', $this->Auth->user());
	}

	public function index()
	{
		if(!$this->request->is('post')) {
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

	public function authentication()
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

	public function authorisation()
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

		$player->role = $role;

		if(!$this->Players->save($player)) {
			$errors = $player->errors('role');
			$this->Flash->error(reset($errors));
		} else {
			$this->Flash->success("Player#$plin has `$role` authorisation");
		}
	}

	public function checks()
	{
	}

	public function routes()
	{
	}

	public function backups()
	{
		$backupShell = new BackupShell();
		$backupShell->initialize();

		$this->set('backups', $backupShell->getBackupFiles());
	}

	public function migrations()
	{
		$migrations = new Migrations();
		$this->set('migrations', $migrations->status());
	}

}
