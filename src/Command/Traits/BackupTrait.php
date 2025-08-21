<?php
declare(strict_types=1);

namespace App\Command\Traits;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use DirectoryIterator;

trait BackupTrait
{
    protected ?array $_config = null;

    protected function config(string $key): string
    {
        if (is_null($this->_config)) {
            $defaults = [
                'target' => ROOT . DS . 'backups' . DS,
                'mysql' => '/usr/bin/mysql',
                'mysqldump' => '/usr/bin/mysqldump',
            ];
            $this->_config = Hash::merge($defaults, Configure::read('Backups'));
        }

        return $this->_config[$key];
    }

    protected function checkApp(string $app): bool
    {
        exec("$app --help 2>&1", $output, $retval);

        return $retval == 0;
    }

    protected function getBackupFiles(): array
    {
        $list = [];
        $path = $this->config('target');

        foreach (new DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            if ($fileInfo->getExtension() !== 'sql') {
                continue;
            }

            $filename = $fileInfo->getFilename();
            $filesize = $fileInfo->getSize();
            $datetime = date('Y-m-d H:i:s', $fileInfo->getMTime());
            $list[] = [$filename, $filesize, $datetime];
        }
        sort($list);

        return $list;
    }

    protected function storeAuth(array $conn, string $app): string
    {
        $auth = tempnam(sys_get_temp_dir(), 'auth');

        file_put_contents(
            $auth,
            sprintf(
                "[%s]\nuser=%s\npassword=\"%s\"\nhost=%s",
                $app,
                $conn['username'],
                empty($conn['password']) ? null : $conn['password'],
                $conn['host'],
            ),
        );

        return $auth;
    }

    protected function tablesTruncateOrder(): array
    {
        return [
            'social_profiles',
            'attributes_items',
            'characters_conditions',
            'characters_imbues',
            'characters_powers',
            'characters_skills',
            'history',
            'lammies',
            'teachings',
            'attributes',
            'items',
            'imbues',
            'conditions',
            'powers',
            'skills',
            'events',
            'characters',
            'manatypes',
            'believes',
            'players',
            'factions',
            'groups',
            'worlds',
        ];
    }
}
