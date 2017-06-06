<?php
namespace App\Shell;

use App\Model\Table\AppTable;
use Cake\Console\ConsoleIo;
use Cake\Console\Shell;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\I18n\FrozenTime;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Association;
use Cake\ORM\Entity;

class BackupShell extends Shell
{
    protected $connection = NULL;
	protected $target = ROOT . DS . 'backups';

    public function __construct()
	{
		parent::__construct();

		$this->connection = ConnectionManager::getConfig('default');
	}

	public function main()
	{
		$files = (new Folder($this->target))->find('.+\.sql');
		sort($files);

		$backups = collection($files)
			->map(function ($file) {
				$fullpath = $this->target . DS . $file;
				$datetime = date('Y-m-d H:i:s', filemtime($fullpath));
				return [ $file, filesize($fullpath), $datetime ];
			})
			->toList();
		$headers = [ 'Filename', 'Size', 'Datetime' ];

		$this->helper('table')->output(array_merge([$headers], $backups));
	}

	public function export()
	{
		$filename = $this->_exportFile();

        $auth = $this->_storeAuth('mysqldump');
		exec(sprintf('%s --defaults-file=%s -t %s %s > %s'
			, 'mysqldump'
			, $auth
			, $this->connection['database']
			, implode($this->_tableOrder(true), ' ')
			, $filename));
		unlink($auth);

		$this->out("Exported database content to file:");
		$this->quiet($filename);
	}

	public function import($sqlname = NULL)
	{
		if(empty($sqlname)) {
			$this->out("Input filename.sql missing.");
			return;
		}

		$filename = $this->_importFile($sqlname);

		if(filesize($filename) == 0) {
			$this->out("Refusing to import empty file!");
			return;
		}

		$this->out("Truncating database tables...");
		foreach($this->_tableOrder(false) as $tbl) {
			$this->loadModel($tbl)->query()->delete()->execute();
		}

        $auth = $this->_storeAuth('mysql');
		exec(sprintf('%s --defaults-extra-file=%s %s < %s'
			, 'mysql'
			, $auth
			, $this->connection['database']
			, $filename));
		unlink($auth);

		$this->out("Imported database content from file:");
		$this->quiet($filename);
	}

	private function _tableOrder($fill = true)
	{
		if($fill) {
			$order =
				[ "believes"
				, "players"
				, "factions"
				, "groups"
				, "worlds"
				, "characters"
				, "manatypes"
				, "attributes"
				, "items"
				, "conditions"
				, "powers"
				, "skills"
				, "spells"
				, "events"
				, "attributes_items"
				, "characters_conditions"
				, "characters_powers"
				, "characters_skills"
				, "characters_spells"
				, "lammies"
				, "teachings"
				];
		} else {
			$order =
				[ "attributes_items"
				, "characters_conditions"
				, "characters_powers"
				, "characters_skills"
				, "characters_spells"
				, "lammies"
				, "teachings"
				, "attributes"
				, "items"
				, "conditions"
				, "powers"
				, "skills"
				, "spells"
				, "events"
				, "characters"
				, "manatypes"
				, "believes"
				, "players"
				, "factions"
				, "groups"
				, "worlds"
				];
		}

		$tables = ConnectionManager::get('default')->schemaCollection()->listTables();
		$count = 0;
		foreach($tables as $name) {
			$table = $this->loadModel($name);
			if($table instanceof AppTable)
				$count++;
		}

		if(count($order) != $count)
			throw new InternalErrorException('Inconsistent table count.');

		return $order;
	}

	protected function _exportFile()
	{
		$filename = sprintf('backup_%s_%s.sql'
						, $this->connection['database']
						, date('YmdHis'));

        if (!Folder::isAbsolute($filename)) {
            $filename = $this->target . DS . $filename;
        }

        if (!is_writable(dirname($filename))) {
            throw new InternalErrorException('File or directory `{0}` not writable', dirname($filename));
        }

        if (file_exists($filename)) {
            throw new InternalErrorException('File `{0}` already exists', $filename);
        }

		return $filename;
	}

	protected function _importFile($import)
	{
		$filename = $import;

        if (!Folder::isAbsolute($filename)) {
            $filename = $this->target . DS . $filename;
        }

        if (!is_readable(dirname($filename))) {
            throw new InternalErrorException(sprintf('File or directory `%s` not readable', dirname($filename)));
        }

        if (!file_exists($filename)) {
            throw new InternalErrorException(sprintf('File `%s` does not exists', $filename));
        }

		return $filename;
	}

    protected function _storeAuth($app)
    {
        $auth = tempnam(sys_get_temp_dir(), 'auth');

        file_put_contents($auth, sprintf(
            "[%s]\nuser=%s\npassword=\"%s\"\nhost=%s",
			$app, $this->connection['username'],
            empty($this->connection['password']) ? null : $this->connection['password'],
            $this->connection['host']
        ));

        return $auth;
    }
}
