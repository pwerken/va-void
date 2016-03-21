<?php
namespace App\Model\Entity;

use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class Lammy
	extends AppEntity
{

	protected $_defaults =
			[ 'printed'     => false
			];

	public function getTarget()
	{
		$name = Inflector::pluralize($this->entity);
		$table = TableRegistry::get($name);

		$keys = [$this->key1, $this->key2];
		$primary = $table->primaryKey();
		if(!is_array($primary)) $primary = [$primary];

		$where = [];
		foreach($primary as $i => $id)
			$where[$name.'.'.$id] = $keys[$i];

		return $table->find('WithContain')->where($where)->first();
	}

}
