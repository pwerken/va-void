<?php
namespace App\Shell;

use App\Model\Entity\Player;
use App\Utility\AuthState;
use App\Utility\CheckConfig;
use Cake\Auth\Storage;
use Cake\Console\Shell;
use Cake\Controller\Component\AuthComponent;

class AdminShell extends Shell
{
	public function initialize()
	{
		AuthState::setAuth($this, -2);
	}

	public function user($field)
	{
		switch($field) {
		case 'id':
			return -2;
		case 'role':
			return 'Super';
		default:
			return NULL;
		}
	}

	public function checks()
	{
		$status = CheckConfig::installation();
		foreach($status as $msg => $ok) {
			if($ok) {
				$this->out($msg);
				continue;
			}
			$this->err($msg);
		}
	}

	public function auth($plin = null, $role = null)
	{
		if(isset($plin)) {
			return $this->authPlayer($plin, $role);
		}

		$this->loadModel('Players');
		$perms = $this->Players->find('list',
					[ 'valueField' => 'id'
					, 'groupField' => 'role'
					])->toArray();

		foreach(array_reverse(Player::roleValues()) as $role) {
			$count = isset($perms[$role]) ? count($perms[$role]) : 0;
			$this->out(sprintf('<warning>%s</warning> (%d)', $role, $count));
			if($count == 0 || $count > 100) {
				continue;
			}

			foreach($perms[$role] as $plin) {
				$player = $this->Players->get($plin);
				$this->out(sprintf('<info>%4d</info> %s', $plin, $player->fullName));
			}
		}
	}

	private function authPlayer($plin, $role)
	{
		$this->loadModel('Players');
		$player = $this->Players->findById($plin)->first();
		if(is_null($player) || strcmp($player->id, $plin)) {
			$this->abort(sprintf('No player found with plin `%s`.', $plin));
		}

		if(isset($role)) {
			$this->Players->patchEntity($player, ['role' => $role]);
			$this->Players->save($player);
			$errors = $player->errors('role');
			if(!empty($errors)) {
				foreach($errors as $error) {
					$this->err($error);
				}
				return 1;
			}
		}

		$this->out(sprintf('<warning>%s</warning> <info>%d</info> %s', $player->role, $player->id, $player->fullName));
	}

	public function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->setDescription(['Admin operations'])
			->addSubcommand('checks',
				[ 'help' => 'Run application configuration and setup checks.'
				])
			->addSubcommand('auth',
				[ 'help' => 'Show/modify user authorizations.'
				, 'parser' =>
					[ 'arguments' =>
						[ 'plin' =>
							[ 'help' => '<plin> of the player to view/modify.'
							, 'required' => false
							]
						, 'role' =>
							[ 'help' => 'The new authorization role to assign to player <plin>.'
							, 'required' => false
							, 'choice' => Player::roleValues()
				]	]	]	])
			->removeOption('verbose');

		foreach($parser->subcommands() as $sub) {
			$sub = $sub->parser();
			if(!$sub) continue;

			$sub->removeOption('verbose');
		}

		return $parser;
	}
}
