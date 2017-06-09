<?php
namespace App\Shell;

use App\Model\Table\AppTable;
use Cake\Console\Shell;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;

class BackupShell extends Shell
{
	protected $target = ROOT . DS . 'backups';

	public function main()
	{
		$this->index();
	}

	public function index()
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
		if(!is_writable($this->target)) {
			$this->err(sprintf('Directory `%s` not writable', $this->target));
			return false;
		}

		$connection = ConnectionManager::getConfig('default');
		$filename = $this->target . DS . sprintf('backup_%s_%s.sql'
						, $connection['database'], date('YmdHis'));

		if(file_exists($filename)) {
			$this->err(sprintf('File `%s` already exists', $filename));
			return false;
		}

		$tables = $this->_tableOrder(true);
		if(empty($tables)) {
			return false;
		}

		$this->out('Exporting database content to file:');
		$this->quiet($filename);

		$auth = $this->_storeAuth($connection, 'mysqldump');
		exec(sprintf('%s --defaults-file=%s -t %s %s > %s'
			, 'mysqldump', $auth, $connection['database']
			, implode($tables, ' '), $filename));
		unlink($auth);

		$this->out('Done');
	}

	public function import($filename = NULL)
	{
		if(empty($filename)) {
			$this->err('Missing `filename` parameter');
			return false;
		}

		if(!Folder::isAbsolute($filename)) {
			$filename = $this->target . DS . $filename;
		}

		if(!file_exists($filename)) {
			$this->err(sprintf('File `%s` does not exists', $filename));
			return false;
		}

		if(!is_readable($filename)) {
			$this->err(sprintf('File `%s` not readable', $filename));
			return false;
		}

		if(filesize($filename) == 0) {
			$this->err(sprintf('File `%s` is empty', $filename));
			return false;
		}

		$tables = $this->_tableOrder(false);
		if(empty($tables)) {
			return false;
		}

		$this->out('Truncating database tables...');
		foreach($tables as $table) {
			$this->loadModel($table)->query()->delete()->execute();
		}

		$this->out("Importing database content from file:");
		$this->quiet($filename);

		$connection = ConnectionManager::getConfig('default');
		$auth = $this->_storeAuth($connection, 'mysql');
		exec(sprintf('%s --defaults-extra-file=%s %s < %s'
			, 'mysql', $auth, $connection['database']
			, $filename));
		unlink($auth);

		$this->out('Done');
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

		$connection = ConnectionManager::get('default');
		$tables = $connection->schemaCollection()->listTables();
		$count = 0;
		foreach($tables as $name) {
			$table = $this->loadModel($name);
			if($table instanceof AppTable)
				$count++;
		}
		if(count($order) != $count) {
			$this->err('Inconsistent table count.');
			return [];
		}

		return $order;
	}

	protected function _storeAuth($conn, $app)
	{
		$auth = tempnam(sys_get_temp_dir(), 'auth');

		file_put_contents($auth
			, sprintf("[%s]\nuser=%s\npassword=\"%s\"\nhost=%s"
				, $app, $conn['username']
				, empty($conn['password'])? NULL : $conn['password']
				, $conn['host']
			)	);

		return $auth;
	}
}
