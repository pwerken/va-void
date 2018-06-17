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
			$data = ['Queued', 'Failed', 'Printing', 'Printed'];
		return $data;
	}

	protected function _getTarget()
	{
		if(is_null($this->target)) {
			$name = Inflector::pluralize($this->entity);
			$table = TableRegistry::get($name);

			$keys = [$this->key1, $this->key2];
			$primary = $table->getPrimaryKey();
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

	public function _setTarget($target = NULL)
	{
		if(is_null($target))
			return;

		$table = TableRegistry::get($target->source());
		$class = $table->entityClass();
		if($pos = strrpos($class, '\\'))
			$class = substr($class, $pos + 1);
		$this->entity = $class;

		$primary = $table->getPrimaryKey();
		if(!is_array($primary))
			$primary = [$primary];

		foreach($primary as $key => $field) {
			$this->set("key".($key+1), $target->get($field));
		}
	}

	protected function _getLammy()
	{
		if(is_null($this->lammy)) {
			$class = 'App\\Lammy\\'.$this->entity.'Lammy';
			$target = $this->_getTarget();
			if(!is_null($target)) {
				$this->lammy = new $class($target);
			}
		}
		return $this->lammy;
	}

}
