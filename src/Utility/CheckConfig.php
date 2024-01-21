<?php
namespace App\Utility;

use Cake\Cache\Cache;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Migrations\Migrations;

class CheckConfig
{
    const PHP_REQUIRED = '8.2.0';
    const ICU_REQUIRED = '50.1';

    public static function installation()
    {
        $status = [];

        if(Security::getSalt() === '__SALT__') {
            $msg = sprintf('Please change the value of %s in %s to a salt value specific to your application.', 'Security.salt', 'ROOT/config/app.php');
            $status[$msg] = false;
        }

        $msg = sprintf('PHP version is %s or higher (detected %s).', self::PHP_REQUIRED, PHP_VERSION);
        if(version_compare(PHP_VERSION, self::PHP_REQUIRED, '>=')) {
            $status[$msg] = true;
        } else {
            $status[$msg] = false;
        }

        if(extension_loaded('intl')) {
            $msg = 'PHP has the intl extension loaded.';
            $status[$msg] = true;
        } else {
            $msg = 'PHP does NOT have the intl extension loaded.';
            $status[$msg] = false;
        }

        $msg = sprintf('ICU >= %s is needed to use CakePHP. (detected %s).', self::ICU_REQUIRED, INTL_ICU_VERSION);
        if(version_compare(INTL_ICU_VERSION, self::ICU_REQUIRED, '>')) {
            $status[$msg] = true;
        } else {
            $status[$msg] = false;
        }

        if(extension_loaded('mbstring')) {
            $msg = 'PHP has the mbstring extension loaded.';
            $status[$msg] = true;
        } else {
            $msg = 'PHP does NOT have the mbstring extension loaded.';
            $status[$msg] = false;
        }

        if(extension_loaded('openssl')) {
            $msg = 'PHP has the openssl extension loaded.';
            $status[$msg] = true;
        } elseif (extension_loaded('mcrypt')) {
            $msg = 'PHP has the mcrypt extension loaded.';
            $status[$msg] = true;
        } else {
            $msg = 'PHP does NOT have the openssl or mcrypt extension loaded.';
            $status[$msg] = false;
        }

        if(is_writable(TMP)) {
            $msg = sprintf("tmp directory (%s) is writable.", TMP);
            $status[$msg] = true;
        } else {
            $msg = sprintf("tmp directory (%s) NOT is writable.", TMP);
            $status[$msg] = false;
        }

        if(is_writable(LOGS)) {
            $msg = sprintf("logs directory (%s) is writable.", LOGS);
            $status[$msg] = true;
        } else {
            $msg = sprintf("logs directory (%s) NOT is writable.", LOGS);
            $status[$msg] = false;
        }

        $settings = Cache::getConfig('_cake_core_');
        if(!empty($settings)) {
            $msg = "cache is working.";
            $status[$msg] = true;
        } else {
            $msg = "cache is NOT working.";
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
            $msg = 'Database table structures are NOT set up using Migrations.';
            $status[$msg] = false;
        } else {
            $msg = false;
            foreach($migrations as $migration) {
                if($migration['status'] == 'up') {
                    continue;
                }
                $msg = 'Database table structures are NOT up to date with Migrations.';
                break;
            }
            if($msg !== false) {
                $status[$msg] = false;
            } else {
                $msg = 'Database table structures are up to date with Migrations.';
                $status[$msg] = true;
            }
        }

        $transport = TransportFactory::get('default');
        if($transport->getConfig('username') && $transport->getConfig('password')) {
            $msg = "EmailTransport configured.";
            $status[$msg] = true;
        } else {
            $msg = "EmailTransport NOT configured.";
            $status[$msg] = false;
        }

        $admin = Mailer::getConfig('default');
        if($admin['to']) {
            $msg = 'Email default "To" set.';
            $status[$msg] = true;
        } else {
            $msg = 'Email default "To" NOT set.';
            $status[$msg] = false;
        }

        return $status;
    }
}
