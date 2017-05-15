<?php
namespace App\Model\Entity;

use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class Lammy
	extends AppEntity
{

	private $target = null;
	private $lammy  = null;

	protected $_defaults =
			[ 'status' => 'Queued'
			];

	protected $_hidden = [ 'lammy' ];

	protected $_virtual = [ 'target', 'lammy' ];

	protected $_compact = [ 'entity', 'key1', 'key2', 'status', 'modified'];

	public static function statusValues()
	{
		static $data = null;
		if(is_null($data))
			$data = ['Queued', 'Printing', 'Printed'];
		return $data;
	}

	protected function _getTarget()
	{
		if(is_null($this->target)) {
			$name = Inflector::pluralize($this->entity);
			$table = TableRegistry::get($name);

			$keys = [$this->key1, $this->key2];
			$primary = $table->primaryKey();
			if(!is_array($primary))
				$primary = [$primary];

			$where = [];
			foreach($primary as $i => $id)
				$where[$name.'.'.$id] = $keys[$i];

			$q = $table->find()->where($where);
			$this->target = $table->findWithContain($q)->first();
		}

		return $this->target;
	}

	protected function _getLammy()
	{
		if(is_null($this->lammy)) {
			$class = 'App\\Lammy\\'.$this->entity.'Lammy';
			$this->lammy = new $class($this->_getTarget());
		}
		return $this->lammy;
	}

}
