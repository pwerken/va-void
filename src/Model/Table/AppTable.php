<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
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
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        TableRegistry::getTableLocator()->get('History')->logDeletion($entity);
    }

    public function beforeFind(EventInterface $event, SelectQuery $query, ArrayObject $options, bool $primary): void
    {
        if($query->clause('limit') == 1)
            return;

        foreach($this->orderBy() as $field => $ord) {
            $f = $this->aliasField($field);
            $query->orderBy([$this->aliasField($field) => $ord]);
        }

        if(!is_array($this->getPrimaryKey())) {
            $event->setResult($query);
            return;
        }

        $query->sql();  // force evaluation of internal state/objects
        foreach($query->clause('join') as $join) {
            if(!$this->hasAssociation($join['table']))
                continue;

            $table = TableRegistry::getTableLocator()->get($join['table']);
            $table->setAlias($join['alias']);

            foreach($table->orderBy() as $field => $ord) {
                $query->orderBy([$table->aliasField($field) => $ord]);
            }
        }

        $event->setResult($query);
    }

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        // drop association data from input
        foreach($this->associations() as $assoc) {
            $prop = $assoc->getProperty();
            if(isset($data[$prop]))
                unset($data[$prop]);
        }

        foreach($data->getArrayCopy() as $field => $value) {
            // drop non-existant fields from input
            if(!$this->hasField($field)) {
                unset($data[$field]);
                continue;
            }

            // trim whitespace from string
            if(is_string($value)) {
                $data[$field] = trim($value);
            }
        }

        // change "" to null for nullable fields
        $schema = $this->getSchema();
        foreach($schema->columns() as $field) {
            if(!$schema->isNullable($field) or !isset($data[$field]))
                continue;

            if(empty($data[$field]))
                $data[$field] = null;
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

        TableRegistry::getTableLocator()->get('History')->logChange($entity);
    }

    public function getWithContain($id)
    {
        return $this->get($id, contain: $this->contain());
    }

    public function findWithContain(?SelectQuery $query = null, mixed ...$args): SelectQuery
    {
        if(is_null($query))
            $query = $this->find('all', ...$args);

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
        $table = TableRegistry::getTableLocator()->get($model);
        $entity = $table->get($id);
        $table->touch($entity);
        $table->save($entity);
    }
}
