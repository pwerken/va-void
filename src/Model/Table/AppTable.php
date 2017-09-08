<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

abstract class AppTable
	extends Table
{

	protected $_contain = [ ];

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->addBehavior('Timestamp');
		$this->addBehavior('CreatorModifier');
	}

	public function findWithContain(Query $query, array $options = [])
	{
		if(!empty($this->_contain))
			$query->contain($this->_contain);

		return $query;
	}

	public function beforeDelete(Event $event, EntityInterface $entity, $options)
	{
		TableRegistry::get('history')->logDeletion($entity);
	}

	public function beforeSave(Event $event, EntityInterface $entity, $options)
	{
		if($entity->isNew())
			return;

		if($entity->dirty('modified') && $entity->dirty('modifier_id')
		&& $entity->get('modifier_id') == $entity->getOriginal('modifier_id')
		&& count($entity->getDirty()) == 2)
			return;

		TableRegistry::get('history')->logChange($entity);
	}

	public function beforeFind(Event $event, Query $query, $options, $primary)
	{
		if($query->clause('limit') == 1)
			return $query;

		foreach($this->orderBy() as $field => $ord) {
			$f = $this->aliasField($field);
			$query->order([$this->aliasField($field) => $ord]);
		}

		if(!is_array($this->primaryKey()))
			return $query;

		$query->sql();	// force evaluation of internal state/objects
		foreach($query->clause('join') as $join) {
			if(!$this->association($join['table']))
				continue;

			$table = TableRegistry::get($join['table']);
			$table->alias($join['alias']);

			foreach($table->orderBy() as $field => $ord) {
				$query->order([$table->aliasField($field) => $ord]);
			}
		}

		return $query;
	}

	protected function orderBy() {
		return [ ];
	}

	protected function touchEntity($model, $id)
	{
		$table = TableRegistry::get($model);
		$entity = $table->get($id);
		$table->touch($entity);
		$table->save($entity);
	}
}
