<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Cache\Cache;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Utility\Security;
use Exception;
use Migrations\Migrations;

class CheckConfig
{
    public const PHP_REQUIRED = '8.2.0';
    public const ICU_REQUIRED = '50.1';

    public static function installation(): array
    {
        $status = [];

        $msg = sprintf(
            'Please change the value of %s in %s to a salt value specific to your application.',
            'Security.salt',
            'ROOT/config/app.php',
        );
        if (Security::getSalt() === '__SALT__') {
            $status[$msg] = false;
        }

        $msg = sprintf('PHP version is %s or higher (detected %s).', self::PHP_REQUIRED, PHP_VERSION);
        $status[$msg] = version_compare(PHP_VERSION, self::PHP_REQUIRED, '>=');

        $msg = 'PHP has the intl extension loaded.';
        $status[$msg] = extension_loaded('intl');

        $msg = sprintf('ICU >= %s is needed to use CakePHP. (detected %s).', self::ICU_REQUIRED, INTL_ICU_VERSION);
        $status[$msg] = version_compare(INTL_ICU_VERSION, self::ICU_REQUIRED, '>');

        $msg = 'PHP has the mbstring extension loaded.';
        $status[$msg] = extension_loaded('mbstring');

        if (extension_loaded('openssl')) {
            $msg = 'PHP has the openssl extension loaded.';
            $status[$msg] = true;
        } elseif (extension_loaded('mcrypt')) {
            $msg = 'PHP has the mcrypt extension loaded.';
            $status[$msg] = true;
        } else {
            $msg = 'PHP does NOT have the openssl or mcrypt extension loaded.';
            $status[$msg] = false;
        }

        $msg = sprintf('TMP directory (%s) is writable.', TMP);
        $status[$msg] = is_writable(TMP);

        $msg = sprintf('LOGS directory (%s) is writable.', LOGS);
        $status[$msg] = is_writable(LOGS);

        $msg = 'Framework cache is working.';
        $status[$msg] = !empty(Cache::getConfig('_cake_translations_'));

        $msg = 'Model cache is working.';
        $status[$msg] = !empty(Cache::getConfig('_cake_model_'));

        $msg = 'CakePHP is able to connect to the database.';
        try {
            ConnectionManager::get('default')->getDriver()->connect();
            $status[$msg] = true;
        } catch (Exception $connectionError) {
            $errorMsg = $connectionError->getMessage();
            if (method_exists($connectionError, 'getAttributes')) {
                $attributes = $connectionError->getAttributes();
                if (isset($errorMsg['message'])) {
                    $errorMsg .= "\n" . $attributes['message'];
                }
            }
            $msg = sprintf('CakePHP is NOT able to connect to the database.\n%s', $errorMsg);
            $status[$msg] = false;

            return $status;
        }

        $migrations = new Migrations();
        $migrations = $migrations->status();

        $msg = 'Database table structures are set up using Migrations.';
        $status[$msg] = !empty($migrations);

        $msg = 'Database table structures are up to date with Migrations.';
        $status[$msg] = !empty($migrations);
        foreach ($migrations as $migration) {
            if ($migration['status'] !== 'up') {
                $status[$msg] = false;
                break;
            }
        }

        $msg = 'EmailTransport configured.';
        $transport = TransportFactory::get('default');
        $status[$msg] = ($transport->getConfig('username') && $transport->getConfig('password'));

        $msg = 'Email default "To" set.';
        $admin = Mailer::getConfig('default');
        $status[$msg] = !empty($admin['to']);

        return $status;
    }
}
