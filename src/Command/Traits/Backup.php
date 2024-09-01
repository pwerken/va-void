<?php
declare(strict_types=1);

namespace App\Command\Traits;

use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Utility\Hash;

trait Backup
{
    protected $_config = null;

    protected function config(string $key)
    {
        if (is_null($this->_config)) {
            $defaults =
                [ 'target' => ROOT . DS . 'backups' . DS
                , 'mysql' => '/usr/bin/mysql'
                , 'mysqldump' => '/usr/bin/mysqldump'
                ];
            $this->_config = (Hash::merge($defaults, Configure::read('Backups')));
        }
        return $this->_config[$key];
    }

    protected function checkApp(string $app): void
    {
        exec("$app --help 2>&1", $output, $retval);
        if($retval <> 0) {
            $this->abort(sprintf("Error executing `%s`, check your config." , $app));
        }
    }

    protected function getBackupFiles()
    {
        $files = (new Folder($this->config('target')))->find('.+\.sql');
        sort($files);

        return collection($files)
            ->map(function ($file) {
                $fullpath = $this->config('target') . $file;
                $datetime = date('Y-m-d H:i:s', filemtime($fullpath));
                return [ $file, filesize($fullpath), $datetime ];
            })
            ->toList();
    }

    protected function storeAuth($conn, $app)
    {
        $auth = tempnam(sys_get_temp_dir(), 'auth');

        file_put_contents($auth
            , sprintf("[%s]\nuser=%s\npassword=\"%s\"\nhost=%s"
                , $app, $conn['username']
                , empty($conn['password'])? NULL : $conn['password']
                , $conn['host']
            )   );

        return $auth;
    }

    protected function tablesTruncateOrder()
    {
        return  [ 'social_profiles'
                , 'attributes_items'
                , 'characters_conditions'
                , 'characters_powers'
                , 'characters_skills'
                , 'history'
                , 'lammies'
                , 'teachings'
                , 'attributes'
                , 'items'
                , 'conditions'
                , 'powers'
                , 'skills'
                , 'events'
                , 'characters'
                , 'manatypes'
                , 'players'
                , 'factions'
                ];
    }
}
