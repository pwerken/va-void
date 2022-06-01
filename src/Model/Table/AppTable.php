<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

abstract class AppTable
	extends Table
{

	public function initialize(array $config): void
	{
		parent::initialize($config);

		$this->addBehavior('Timestamp');
#		$this->addBehavior('CreatorModifier');
	}

	public function findWithContain(Query $query, array $options = [])
	{
		$contain = $this->contain();
		if(!empty($contain))
			$query->contain($contain);

		return $query;
	}

	public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
	{
		TableRegistry::get('History')->logDeletion($entity);
	}

	public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
	{
		if($entity->isNew())
			return;

		if($entity->isDirty('modified') && $entity->isDirty('modifier_id')
		&& $entity->get('modifier_id') == $entity->getOriginal('modifier_id')
		&& count($entity->getDirty()) == 2)
			return;

		TableRegistry::get('History')->logChange($entity);
	}

	public function beforeFind(EventInterface $event, Query $query, ArrayObject $options, bool $primary): Query
	{
		if($query->clause('limit') == 1)
			return $query;

		foreach($this->orderBy() as $field => $ord) {
			$f = $this->aliasField($field);
			$query->order([$this->aliasField($field) => $ord]);
		}

		if(!is_array($this->getPrimaryKey()))
			return $query;

		$query->sql();	// force evaluation of internal state/objects
		foreach($query->clause('join') as $join) {
			if(!$this->hasAssociation($join['table']))
				continue;

			$table = TableRegistry::get($join['table']);
			$table->setAlias($join['alias']);

			foreach($table->orderBy() as $field => $ord) {
				$query->order([$table->aliasField($field) => $ord]);
			}
		}

		return $query;
	}

	protected function contain(): array
	{
		return [ ];
	}

	protected function orderBy(): array
	{
		return [ ];
	}

	protected function touchEntity($model, $id): void
	{
		$table = TableRegistry::get($model);
		$entity = $table->get($id);
		$table->touch($entity);
		$table->save($entity);
	}
}
