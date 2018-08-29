<?php
namespace App\Controller;

use App\Model\Entity\Player;
use App\Shell\BackupShell;
use App\Utility\AuthState;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;
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

		$this->set('user', $user);
	}

	public function isAuthorized($user)
	{
		AuthState::setAuth($this->Auth, $user['id']);

		if(AuthState::hasRole('Super'))
			return true;

		switch($this->request->action) {
		case 'authorisation':
		case 'history':
		case 'printing':
			return AuthState::hasRole('Read-only');
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
		if(!AuthState::hasRole('user') && !AuthState::hasRole('Super')) {
			$this->Flash->error("Not authorized to change password for Player#$plin");
			return;
		}

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

	public function history($e = NULL, $k1 = NULL, $k2 = NULL)
	{
		$table = $this->loadModel('history');
		if(!is_null($e)) {
			$this->viewBuilder()->setTemplate('historyEntity');
			$list = $table->getEntityHistory($e, $k1, $k2);
			if(empty($list)) {
				throw new NotFoundException();
			}
		} else {
			$plin = $this->request->data('plin');
			$since = $this->request->data('since');
			if(empty($since)) {
				$date = new \DateTime();
				$date->sub(new \DateInterval('P3M'));
				$since = $date->format('Y-m-d');
			}

			$this->set('plin', $plin);
			$this->set('since', $since);
			$list = $table->getAllLastModified($since, $plin);
		}

		$this->set('list', $list);
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
			if(!empty($ids)) {
				$nr = $lammies->deleteAll(['id IN' => $ids]);
				$this->Flash->success("Removed $nr lammies from queue");
			}
		}

		$since = (new \DateTime())->sub(new \DateInterval('P3M'));
		$query = $lammies->find();
		$query
			->select($lammies)
			->select(['character' => $query->func()->concat(
				[ 'characters.player_id' => 'identifier', '/'
				, 'characters.chin' => 'identifier'])])
			->leftJoin(['characters']
			         , [ 'lammies.entity LIKE' => 'Character%'
					   , 'lammies.key1 = characters.id'])
			->where(['lammies.modified >' => $since->format('Y-m-d')])
			->orderDesc('lammies.id')
			->hydrate(false);
		$this->set('printing', $query->all());
	}

	public function valea()
	{
		if($this->request->is('post')
		&& AuthState::hasRole('Infobalie')
		&& isset($this->request->data['action'])
		&& is_array($this->request->data['action'])
		) {
			$done = [];
			$done['insert'] = [];
			$done['update'] = [];
			$done['delete'] = [];

			foreach($this->request->data['action'] as $plin => $action) {
				$ret = false;
				switch($action) {
				case 'insert': $ret = $this->voidValeaImport($plin); break;
				case 'update': $ret = $this->voidValeaUpdate($plin); break;
				case 'delete': $ret = $this->voidDelete($plin);      break;
				}
				if(!$ret) {
					$this->Flash->error('FAILED: ' . $action . ' #' . $plin);
					break;
				}
				$done[$action][] = $plin;
			}
			$str = '';
			if(count($done['insert']) > 0) {
				$str .= 'Added:#'.implode(',#', $done['insert']);
			}
			if(count($done['update']) > 0) {
				if(strlen($str) > 0) $str .= '; ';
				$str .= 'Updated:#'.implode(',#', $done['update']);
			}
			if(count($done['delete']) > 0) {
				if(strlen($str) > 0) $str .= '; ';
				$str .= 'Deleted:#'.implode(',#', $done['delete']);
			}
			if(strlen($str) > 0) $this->Flash->success($str);
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
			return false;

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

		return $this->loadModel('players')->save(new Player($data));
	}
	private function voidValeaUpdate($plin)
	{
		if(is_null($plin))
			return false;

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

		return $players->save($player);
	}
	private function voidDelete($plin)
	{
		if(is_null($plin))
			return;

		$players = $this->loadModel('Players');
		$player = $players->get($plin);

		return $players->delete($player);
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
