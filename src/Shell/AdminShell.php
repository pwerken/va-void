<?php
namespace App\Shell;

use App\Model\Entity\Player;
use App\Utility\CheckConfig;
use Cake\Controller\Component\AuthComponent;

class AdminShell extends AppShell
{

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
			$errors = $player->getErrors('role');
			if(!empty($errors)) {
				foreach($errors as $error) {
					$this->err($error);
				}
				return 1;
			}
		}

		$this->out(sprintf('<warning>%s</warning> <info>%d</info> %s', $player->role, $player->id, $player->fullName));
	}

	public function password($plin)
	{
		$this->loadModel('Players');
		$player = $this->Players->findById($plin)->first();
		if(is_null($player) || strcmp($player->id, $plin)) {
			$this->abort(sprintf('No player found with plin `%s`.', $plin));
		}

		if($this->params['remove']) {
			$player->password = NULL;
			$msg = 'Password removed';
		} else {
			$player->set('password', $this->prompt_silent());
			$msg = 'Password set';
		}

		$this->Players->save($player);
		$errors = $player->getErrors('password');
		if(!empty($errors)) {
			foreach($errors as $error) {
				$this->err($error);
			}
			return 1;
		}

		$this->out(sprintf('<info>%4d</info> %s: <warning>%s</warning>', $player->id, $player->fullName, $msg));
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
			->addSubcommand('password',
				[ 'help' => 'Set/remove user password.'
				, 'parser' =>
					[ 'options' =>
						[ 'remove' =>
							[ 'help' => 'Remove password instead of setting it.'
							, 'required' => false
							, 'boolean' => true
						]	]
					, 'arguments' =>
						[ 'plin' =>
							[ 'help' => '<plin> of the player to view/modify.'
							, 'required' => true
				]	]	]	])
			->removeOption('verbose');

		foreach($parser->subcommands() as $sub) {
			$sub = $sub->parser();
			if(!$sub) continue;

			$sub->removeOption('verbose');
		}

		return $parser;
	}


	/**
	 * Interactively prompts for input without echoing to the terminal.
	 * Requires a bash shell or Windows and won't work with
	 * safe_mode settings (Uses `shell_exec`)
	 */
	private function prompt_silent($prompt = "Enter Password:")
	{
		if (preg_match('/^win/i', PHP_OS)) {
			$vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
			file_put_contents(
				$vbscript, 'wscript.echo(InputBox("'
				. addslashes($prompt)
				. '", "", "password here"))');
			$command = "cscript //nologo " . escapeshellarg($vbscript);
			$password = rtrim(shell_exec($command));
			unlink($vbscript);
			return $password;
		} else {
			$this->out('<question>'.$prompt.'</question>');
			$command = "/usr/bin/env bash -c 'read -s -p \"\" mypassword && echo \$mypassword'";
			$password = rtrim(shell_exec($command));
			return $password;
		}
	}

}
