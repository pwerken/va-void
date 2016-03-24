<?php
namespace App\Model\Entity;

use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class Lammy
	extends AppEntity
{

	private $target = null;

	protected $_defaults =
			[ 'printed'     => false
			];

	protected $_virtual = [ 'target' ];

	protected function _getTarget()
	{
		if(is_null($this->target)) {
			$name = Inflector::pluralize($this->entity);
			$table = TableRegistry::get($name);

			if(strcmp($name, 'Characters') == 0) {
				$q = $table->findByPlayerIdAndChin($this->key1, $this->key2);
			} else {
				$keys = [$this->key1, $this->key2];
				$primary = $table->primaryKey();
				if(!is_array($primary)) $primary = [$primary];

				$where = [];
				foreach($primary as $i => $id)
					$where[$name.'.'.$id] = $keys[$i];

				$q = $table->find()->where($where);
			}
			$this->target = $table->findWithContain($q)->first();
		}

		return $this->target;
	}

}
