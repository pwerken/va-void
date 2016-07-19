<?php
namespace App\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

abstract class AppTable
	extends Table
{

	protected $_contain = [ ];

	public function findWithContain(Query $query, array $options = [])
	{
		if(!empty($this->_contain))
			$query->contain($this->_contain);

		return $query;
	}

	public function beforeFind(Event $event, Query $query, $options, $primary)
	{
		foreach($this->orderBy() as $field => $ord) {
			$f = $this->aliasField($field);
			$query->order([$this->aliasField($field) => $ord]);
		}

		$query->sql();
		foreach($query->clause('join') as $join) {
			$table = TableRegistry::get($join['table']);
			$table->alias($join['alias']);

			foreach($table->orderBy() as $field => $ord) {
				$f = $table->aliasField($field);
				$query->order([$table->aliasField($field) => $ord]);
			}
		}

		return $query;
	}

	public function orderBy() {
		return [ ];
	}

}
