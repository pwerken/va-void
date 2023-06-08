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
    protected $referencesPresent = ['consistency' => 'Reference(s) present'];

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('WhoWhen');

        $entityName = $this->getEntityClass();
        $entityName = substr($entityName, strrpos($entityName, '\\') + 1);
        $this->_validatorClass = "App\\Model\\Validation\\{$entityName}Validator";
    }

    public function afterMarshal(EventInterface $event, EntityInterface $entity, ArrayObject $data, ArrayObject $options): void
    {
        /* disallow modification of primary key field(s) */
        if(!$entity->isNew()) {
            $keys = $this->getPrimaryKey();
            if(is_string($keys)) {
                $keys = [$keys];
            }
            foreach($keys as $key)
            {
                if($entity->isDirty($key)) {
                    $entity->setError($key, ['key' => 'Cannot change primary key field']);
                }
            }
        }

        /* change "" to null for nullable fields */
        $schema = $this->getSchema();
        foreach($schema->columns() as $field)
        {
            if($schema->isNullable($field) and empty($entity->$field))
            {
                $entity->$field = NULL;
            }
        }
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        TableRegistry::get('History')->logDeletion($entity);
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

        $query->sql();  // force evaluation of internal state/objects
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

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        // drop non-existant fields from input
        // also prevents marshalling of associations
        foreach($data as $field => $value) {
            if(!$this->hasField($field))
                unset($data[$field]);
        }
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if($entity->isNew()) {
            return;
        }

        if($entity->isDirty('modified') && $entity->isDirty('modifier_id')
        && $entity->get('modifier_id') == $entity->getOriginal('modifier_id')
        && count($entity->getDirty()) == 2)
        {
            return;
        }

        TableRegistry::get('History')->logChange($entity);
    }

    public function getWithContain($id)
    {
        return $this->get($id, ['contain' => $this->contain()]);
    }

    public function findWithContain(Query $query = null, array $options = []): Query
    {
        if(is_null($query))
            $query = $this->find('all', $options);

        $contain = $this->contain();
        if(!empty($contain))
            $query->contain($contain);

        return $query;
    }

    protected function contain(): array
    {
        return [];
    }

    protected function orderBy(): array
    {
        return [];
    }

    protected function touchEntity($model, $id): void
    {
        $table = TableRegistry::get($model);
        $entity = $table->get($id);
        $table->WhoWhen->touch($entity);
        $table->save($entity);
    }
}
