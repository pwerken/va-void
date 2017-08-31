<?php
namespace App\Shell;

use App\Model\Table\AppTable;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\Utility\Hash;

class BackupShell extends Shell
{
	protected $config;

	public function initialize()
	{
		parent::initialize();

		$defaults =
			[ 'target' => ROOT . DS . 'backups' . DS
			, 'mysql' => '/usr/bin/mysql'
			, 'mysqldump' => '/usr/bin/mysqldump'
			];

		$this->config = (Hash::merge($defaults, Configure::read('Backups')));
	}

	public function main()
	{
		$this->checkApp($this->config['mysql']);
		$this->checkApp($this->config['mysqldump']);

		$this->index();
	}

	public function index()
	{
		$backups = $this->getBackupFiles();
		$headers = [ 'Filename', 'Size', 'Datetime' ];

		$this->helper('table')->output(array_merge([$headers], $backups));
	}

	public function export($descr = NULL)
	{
		$target = $this->config['target'];
		if(!is_writable($target)) {
			$this->err(sprintf('Directory `%s` not writable', $target));
			return false;
		}

		if(is_null($descr)) {
			$descr = '';
		} else {
			$descr = '_'.preg_replace("/[^a-zA-Z0-9]+/", "_", $descr);;
		}
		$filename = sprintf('%s%s%s.sql', $target , date('YmdHis'), $descr);

		if(file_exists($filename)) {
			$this->err(sprintf('File `%s` already exists', $filename));
			return false;
		}

		$tables = $this->_tableOrder(true);
		if(empty($tables)) {
			return false;
		}

		if(!$this->checkApp($this->config['mysqldump'])) {
			return false;
		}

		$this->out('Exporting database content to file:');
		$this->quiet($filename);

		$connection = ConnectionManager::getConfig('default');
		$auth = $this->_storeAuth($connection, 'mysqldump');
		$cmd = sprintf('%s --defaults-file=%s -t --result-file=%s %s %s'
					, $this->config['mysqldump'], $auth
					, $filename, $connection['database']
					, implode($tables, ' '));
		$this->verbose("exec: $cmd");
		exec($cmd);
		unlink($auth);

		if(!file_exists($filename)) {
			$this->err(sprintf('File `%s` not created', $filename));
			return false;
		}

		if(filesize($filename) == 0) {
			$this->err(sprintf('File `%s` is empty', $filename));
			return false;
		}

		$this->out('Done');
	}

	public function import($filename = NULL)
	{
		if(!Folder::isAbsolute($filename)) {
			$filename = Configure::read('Backups.target') . $filename;
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

		if(!$this->checkApp($this->config['mysql'])) {
			return false;
		}

		$this->out('Truncating database tables...');
		$sql = 'ALTER TABLE `%s` auto_increment = 1;';
		foreach($tables as $table) {
			$this->verbose("- $table");

			$model = $this->loadModel($table);
			$model->query()->delete()->execute();
			$model->connection()->execute(sprintf($sql, $model->table()));
		}

		$this->out("Importing database content from file:");
		$this->quiet($filename);

		$connection = ConnectionManager::getConfig('default');
		$auth = $this->_storeAuth($connection, 'mysql');
		$cmd = sprintf('%s --defaults-extra-file=%s %s < %s'
					, $this->config['mysql'], $auth
					, $connection['database'], $filename);
		$this->verbose("exec: $cmd");
		exec($cmd);
		unlink($auth);

		$this->out('Done');
	}

	public function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->setDescription('Shell to handle database backups.');

		$parser->addSubcommand('index',
			[ 'help' => 'List database backups.' ]);

		$parser->addSubcommand('export',
			[ 'help' => 'Exports a database backup.'
			, 'parser' =>
				[ 'arguments' =>
					[ 'description' =>
						[ "help" => "Append [description] to created backup sql-file."
			]	]	]	]);

		$parser->addSubcommand('import',
			[ 'help' => 'Imports a database backup.'
			, 'parser' =>
				[ 'arguments' =>
					[ 'filename' =>
						[ "help" => "Backup sql-file to import.\nIt can be an absolute path."
						, 'required' => true
			]	]	]	]);

		return $parser;
	}

	public function getBackupFiles()
	{
		$files = (new Folder($this->config['target']))->find('.+\.sql');
		sort($files);

		return collection($files)
			->map(function ($file) {
				$fullpath = $this->config['target'] . $file;
				$datetime = date('Y-m-d H:i:s', filemtime($fullpath));
				return [ $file, filesize($fullpath), $datetime ];
			})
			->toList();
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
				, "audits"
				];
		} else {
			$order =
				[ "attributes_items"
				, "characters_conditions"
				, "characters_powers"
				, "characters_skills"
				, "characters_spells"
				, "audits"
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
			$this->warn('Inconsistent table count.');
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

	protected function checkApp($app)
	{
		exec("$app --help 2>&1", $output, $retval);

		if($retval <> 0) {
			$this->err(sprintf("Error executing `%s`, check your config."
							, $app));
			return false;
		}

		return true;
	}
}
