<?php
namespace App\Controller;

use App\Error\Exception\ConfigurationException;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Migrations\Migrations;

class RootController
	extends AppController
{
	public static $jsonResponse = false;

	public function initialize()
	{
		parent::initialize();

		$this->Auth->allow(['root']);
	}

	public function root()
	{
		if(Configure::read('debug')) {
			$this->installationCheck();
		}

		$this->set('user', $user = $this->Auth->user());
		$this->render('root');
	}

	public function cors()
	{
	}

	protected function installationCheck()
	{
		$errors = [];

		if(Security::salt() === '__SALT__') {
			$errors[] = sprintf('Please change the value of %s in %s to a salt value specific to your application.', 'Security.salt', 'ROOT/config/app.php');
		}

		if(!version_compare(PHP_VERSION, '5.6.0', '>=')) {
			$errors[] = sprintf("You need PHP 5.6.0 or higher to use CakePHP (detected %s).", PHP_VERSION);
		}

		if(!extension_loaded('mbstring')) {
			$errors[] = "Your version of PHP does NOT have the mbstring extension loaded.";
		}

		if(!extension_loaded('openssl') && !extension_loaded('mcrypt')) {
			$errors[] = "Your version of PHP does NOT have the openssl or mcrypt extension loaded.";
		}

		if(!extension_loaded('intl')) {
			$errors[] = "Your version of PHP does NOT have the intl extension loaded.";
		}

		if(!is_writable(TMP)) {
			$errors[] = "Your tmp directory is NOT writable.";
		}

		if(!is_writable(LOGS)) {
			$errors[] = "Your logs directory is NOT writable.";
		}

		$settings = Cache::getConfig('_cake_core_');
		if(empty($settings)) {
			$errors[] = "Your cache is NOT working.";
		}

		try {
			$connection = ConnectionManager::get('default');
			$connected = $connection->connect();
		} catch (Exception $connectionError) {
			$errorMsg = $connectionError->getMessage();
			if(method_exists($connectionError, 'getAttributes')) {
				$attributes = $connectionError->getAttributes();
				if(isset($errorMsg['message'])) {
					$errorMsg .= "\n" .  $attributes['message'];
				}
			}
			$errors[] = sprintf("CakePHP is NOT able to connect to the database.\n%s", $errorMsg);
		}

		$migrations = new Migrations();
		$status = $migrations->status();
		if(empty($status)) {
			$errors[] = "Database tables NOT set up using Migrations.";
		} else {
			foreach($status as $migration) {
				if($migration['status'] == 'up') {
					continue;
				}
				$errors[] = "Database tables NOT up to date with Migrations.";
			}
		}

		if(!empty($errors)) {
			throw new ConfigurationException($errors);
		}
	}
}
