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
		$this->index();
	}

	public function index()
	{
		$files = (new Folder($this->config['target']))->find('.+\.sql');
		sort($files);

		$backups = collection($files)
			->map(function ($file) {
				$fullpath = $this->config['target'] . $file;
				$datetime = date('Y-m-d H:i:s', filemtime($fullpath));
				return [ $file, filesize($fullpath), $datetime ];
			})
			->toList();
		$headers = [ 'Filename', 'Size', 'Datetime' ];

		$this->helper('table')->output(array_merge([$headers], $backups));
	}

	public function export()
	{
		$target = $this->config['target'];
		if(!is_writable($target)) {
			$this->err(sprintf('Directory `%s` not writable', $target));
			return false;
		}

		$connection = ConnectionManager::getConfig('default');
		$filename = sprintf('%sbackup_%s_%s.sql', $target
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
		$cmd = sprintf('%s --defaults-file=%s -t --result-file=%s %s %s'
					, $this->config['mysqldump'], $auth
					, $filename, $connection['database']
					, implode($tables, ' '));
		$this->verbose("exec: $cmd");
		exec($cmd);
		unlink($auth);

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

		$this->out('Truncating database tables...');
		foreach($tables as $table) {
			$this->verbose("- $table");
			$this->loadModel($table)->query()->delete()->execute();
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
			[ 'help' => 'Exports a database backup.' ]);

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
