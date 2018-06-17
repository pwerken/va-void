<?php
namespace App\Utility;

use Cake\Cache\Cache;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Migrations\Migrations;

class CheckConfig
{
	public static function installation()
	{
		$status = [];

		if(Security::getSalt() === '__SALT__') {
			$msg = sprintf('Please change the value of %s in %s to a salt value specific to your application.', 'Security.salt', 'ROOT/config/app.php');
			$status[$msg] = false;
		}

		if(version_compare(PHP_VERSION, '5.6.0', '>=')) {
			$msg = sprintf('Your version of PHP is 5.6.0 or higher (detected %s).', PHP_VERSION);
			$status[$msg] = true;
		} else {
			$msg = sprintf('You need PHP 5.6.0 or higher to use CakePHP (detected %s).', PHP_VERSION);
			$status[$msg] = false;
		}

		if(extension_loaded('mbstring')) {
			$msg = 'Your version of PHP has the mbstring extension loaded.';
			$status[$msg] = true;
		} else {
			$msg = 'Your version of PHP does NOT have the mbstring extension loaded.';
			$status[$msg] = false;
		}

		if(extension_loaded('openssl')) {
            $msg = 'Your version of PHP has the openssl extension loaded.';
			$status[$msg] = true;
		} elseif (extension_loaded('mcrypt')) {
            $msg = 'Your version of PHP has the mcrypt extension loaded.';
			$status[$msg] = true;
		} else {
			$msg = 'Your version of PHP does NOT have the openssl or mcrypt extension loaded.';
			$status[$msg] = false;
		}

		if(extension_loaded('intl')) {
            $msg = 'Your version of PHP has the intl extension loaded.';
			$status[$msg] = true;
		} else {
			$msg = 'Your version of PHP does NOT have the intl extension loaded.';
			$status[$msg] = false;
		}

		if(is_writable(TMP)) {
			$msg = "Your tmp directory is writable.";
			$status[$msg] = true;
		} else {
			$msg = "Your tmp directory is NOT writable.";
			$status[$msg] = false;
		}

		if(is_writable(LOGS)) {
			$msg = "Your logs directory is writable.";
			$status[$msg] = true;
		} else {
			$msg = "Your logs directory is NOT writable.";
			$status[$msg] = false;
		}

		$settings = Cache::getConfig('_cake_core_');
		if(!empty($settings)) {
			$msg = "Your cache is working.";
			$status[$msg] = true;
		} else {
			$msg = "Your cache is NOT working.";
			$status[$msg] = false;
		}

		try {
			$connection = ConnectionManager::get('default');
			$connected = $connection->connect();

			$msg = 'CakePHP is able to connect to the database.';
			$status[$msg] = true;
		} catch (Exception $connectionError) {
			$errorMsg = $connectionError->getMessage();
			if(method_exists($connectionError, 'getAttributes')) {
				$attributes = $connectionError->getAttributes();
				if(isset($errorMsg['message'])) {
					$errorMsg .= "\n" .  $attributes['message'];
				}
			}

			$msg = sprintf('CakePHP is NOT able to connect to the database.\n%s', $errorMsg);
			$status[$msg] = false;
			return $status;
		}

		$migrations = new Migrations();
		$migrations = $migrations->status();
		if(empty($migrations)) {
			$msg = 'Database table structures NOT set up using Migrations.';
			$status[$msg] = false;
		} else {
			$msg = false;
			foreach($migrations as $migration) {
				if($migration['status'] == 'up') {
					continue;
				}
				$msg = 'Database table structures NOT up to date with Migrations.';
				break;
			}
			if($msg !== false) {
				$status[$msg] = false;
			} else {
				$msg = 'Database table structures up to date with Migrations.';
				$status[$msg] = true;
			}
		}

		return $status;
	}
}
