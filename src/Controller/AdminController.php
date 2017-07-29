<?php
namespace App\Controller;

use App\Model\Entity\Player;
use App\Shell\BackupShell;
use App\Utility\AuthState;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
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

	public function printing()
	{
		$lammies = $this->loadModel('lammies');

		if($this->request->is('post')) {
			$ids = $this->request->data('delete');
			// delete queued items
		}

		$query = $lammies->find()->order(['id' => 'DESC']);
		$this->set('printing', $query->all());
	}

	public function valea()
	{
		$q1 = 'SELECT `id`, `first_name`, `insertion`,'
		   	. ' `last_name`, `date_of_birth`, `gender`'
			. ' FROM `players`'
			. ' ORDER BY `id`';
		$void = ConnectionManager::get('default')->query($q1);

		$q2 = 'SELECT `plin`, `voornaam`, `tussenvoegsels`,'
		   	. ' `achternaam`, `geboortedatum`, `mv`'
			. ' FROM `deelnemers`'
			. ' ORDER BY `plin`, `id`';
		$valea = ConnectionManager::get('valea')->query($q2);

		$blank = [NULL,NULL,NULL,NULL,NULL,NULL];
		$diff = [];
		$playerVoid = $void->fetch();
		$playerValea = $valea->fetch();
		while($playerVoid !== False || $playerValea !== False)
		{
			if($playerVoid !== False) {
				if(strtoupper($playerVoid[4]) == '1980-01-01')
					$playerVoid[4] = '';
			}
			if($playerValea !== False) {
				if(strtoupper($playerValea[4]) == '0000-00-00')
					$playerValea[4] = '';
				if($playerValea[5] == '0')
					$playerValea[5] = '';
				if(strtoupper($playerValea[5]) == 'V')
					$playerValea[5] = 'F';
			}

			$cmp = [];
			switch(self::playerCmp($playerVoid, $playerValea)) {
			case 1:
				for($i = 0; $i < 6; $i++)	
					$cmp[] = [true, $playerVoid[$i], NULL];

				$diff[] = ['onlyVoid', $cmp];
				$playerVoid = $void->fetch();
				continue;
			case -1:
				for($i = 0; $i < 6; $i++)	
					$cmp[] = [true, NULL, $playerValea[$i]];

				$diff[] = ['onlyValea', $cmp];
				$playerValea = $valea->fetch();
				continue;
			default:
				$same = true;
				for($i = 0; $i < 6; $i++) {
					$field = $playerVoid[$i] == $playerValea[$i];
					$same &= $field;
					$cmp[] = [$field, $playerVoid[$i], $playerValea[$i]];
				}
				$diff[] = [($same?"same":"different"), $cmp];
				$playerVoid = $void->fetch();
				$playerValea = $valea->fetch();
			}
		}

		$this->set('diff', $diff);
	}

	private function playerCmp($void, $valea) {
		if($void === false)
			return -1;
		if($valea === false)
			return 1;
	
		if($valea[0] === null)	// geen plin!
			return -1;
	
		$cmp = $valea[0] - $void[0];
		if($cmp < 0)
			return -1;
		if($cmp > 0)
			return 1;

		return 0;
	}
}
