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
				]	]
			, 'authorize' => ['Controller']
			, 'unauthorizedRedirect' => false
			, 'checkAuthIn' => 'Controller.initialize'
			, 'loginAction' => '/admin'
			]);

		$user = $this->Auth->user();
		AuthState::setAuth($this->Auth, $user['id']);

		$this->Auth->allow(['index', 'logout', 'checks', 'routes']);

		$this->viewBuilder()->setLayout('admin');
		$this->set('user', $user);
	}

	public function isAuthorized($user)
	{
		AuthState::setAuth($this->Auth, $user['id']);

		if(AuthState::hasRole('Super'))
			return true;

		switch($this->request->action) {
		case 'authorisation':
			return AuthState::hasRole('Referee');
		case 'printing':
		case 'valea':
			return AuthState::hasRole('Infobalie');
		}
		return AuthState::hasRole('Player');
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

		$this->set('backups', array_reverse($backupShell->getBackupFiles()));
	}

	public function migrations()
	{
		$migrations = new Migrations();
		$this->set('migrations', array_reverse($migrations->status()));
	}

	public function printing()
	{
		$lammies = $this->loadModel('lammies');

		if($this->request->is('post') && AuthState::hasRole('Infobalie')) {
			$ids = $this->request->data('delete');
			if(empty($ids)) {
				$this->Flash->error("Nothing selected");
			} else {
				$nr = $lammies->deleteAll(['id IN' => $ids]);
				$this->Flash->success("Removed $nr lammies from queue");
			}
		}

		$query = $lammies->find()->order(['id' => 'DESC'])->hydrate(false);
		$this->set('printing', $query->all());
	}

	public function valea()
	{
		if($this->request->is('post') && AuthState::hasRole('Infobalie')) {
			$this->voidValeaImport($this->request->data('insert'));
			$this->voidValeaUpdate($this->request->data('update'));
			$this->voidDelete($this->request->data('delete'));
		}

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

				$diff[] = [1, $cmp];
				$playerVoid = $void->fetch();
				continue;
			case -1:
				for($i = 0; $i < 6; $i++)
					$cmp[] = [true, NULL, $playerValea[$i]];

				$diff[] = [-1, $cmp];
				$playerValea = $valea->fetch();
				continue;
			default:
				$same = true;
				for($i = 0; $i < 6; $i++) {
					$field = $playerVoid[$i] == $playerValea[$i];
					$same &= $field;
					$cmp[] = [$field, $playerVoid[$i], $playerValea[$i]];
				}
				if(!$same) {
					$diff[] = [0, $cmp];
				}
				$playerVoid = $void->fetch();
				$playerValea = $valea->fetch();
			}
		}

		$this->set('diff', $diff);
	}

	private function voidValeaImport($plin)
	{
		if(is_null($plin))
			return;

		$q = 'SELECT `plin` as "id", `voornaam` as "first_name"'
			. ', `tussenvoegsels` as "insertion"'
			. ', `achternaam` as "last_name"'
			. ', `geboortedatum` as "date_of_birth"'
			. ', `mv` as "gender"'
			. ' FROM `deelnemers`'
			. ' WHERE `plin` = ?';

		$valea = ConnectionManager::get('valea');
		$data = $valea->execute($q, [$plin])->fetch('assoc');

		switch(strtoupper($data['gender'])) {
		case 'M':	$data['gender'] = 'M'; break;
		case 'V':	$data['gender'] = 'F'; break;
		case 'F':	$data['gender'] = 'F'; break;
		default:	$data['gender'] = ''; break;
		}

		$player = new Player($data);

		if($this->loadModel('players')->save($player)) {
			$this->Flash->success('Imported plin #'.$plin);
		} else {
			$this->Flash->error('Failed to import plin #'.$plin);

			var_dump($player->errors());
			die;
		}
	}
	private function voidValeaUpdate($plin)
	{
		if(is_null($plin))
			return;

		$q = 'SELECT `plin` as "id", `voornaam` as "first_name"'
			. ', `tussenvoegsels` as "insertion"'
			. ', `achternaam` as "last_name"'
			. ', `geboortedatum` as "date_of_birth"'
			. ', `mv` as "gender"'
			. ' FROM `deelnemers`'
			. ' WHERE `plin` = ?';

		$valea = ConnectionManager::get('valea');
		$data = $valea->execute($q, [$plin])->fetch('assoc');

		switch(strtoupper($data['gender'])) {
		case 'M':	$data['gender'] = 'M'; break;
		case 'V':	$data['gender'] = 'F'; break;
		case 'F':	$data['gender'] = 'F'; break;
		default:	$data['gender'] = ''; break;
		}

		$players = $this->loadModel('players');
		$player = $players->get($plin);
		$player = $players->patchEntity($player, $data);

		if($players->save($player)) {
			$this->Flash->success('Updated plin #'.$plin);
		} else {
			$this->Flash->error('Failed to update plin #'.$plin);
		}
	}
	private function voidDelete($plin)
	{
		if(is_null($plin))
			return;

		$players = $this->loadModel('Players');
		$player = $players->get($plin);

		if($players->delete($player)) {
			$this->Flash->success('Removed Player#'.$plin.' from void');
		} else {
			$this->Flash->error('Failed to delete Player#'.$plin);
		}
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
