<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table as CakeTable;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use ReflectionClass;

abstract class Table extends CakeTable
{
    protected array $consistencyError = ['consistency' => 'Reference(s) present'];

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('WhoWhen');

        $entityName = (new ReflectionClass($this->getEntityClass()))->getShortName();
        $this->_validatorClass = "App\\Model\\Validation\\{$entityName}Validator";
    }

    public function beforeFind(EventInterface $event, SelectQuery $query, ArrayObject $options, bool $primary): void
    {
        if ($query->clause('limit') == 1) {
            return;
        }

        foreach ($this->orderBy() as $field => $ord) {
            $query->orderBy([$this->aliasField($field) => $ord]);
        }
    }

    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options): void
    {
        // drop association data from input
        foreach ($this->associations() as $assoc) {
            $prop = $assoc->getProperty();
            unset($data[$prop]);
        }

        // drop non-existant fields from input
        foreach ($data->getArrayCopy() as $field => $value) {
            if (!$this->hasField($field)) {
                unset($data[$field]);
                continue;
            }

            // trim whitespace from string
            if (is_string($value)) {
                $data[$field] = trim($value);
            }
        }

        // change "" to null for nullable fields
        $schema = $this->getSchema();
        foreach ($schema->columns() as $field) {
            if (!$schema->isNullable($field) || !isset($data[$field])) {
                continue;
            }

            if (empty($data[$field])) {
                $data[$field] = null;
            }
        }
    }

    public function afterMarshal(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // disallow modification of primary key field(s)
        if ($entity->isNew()) {
            return;
        }

        $keys = $this->getPrimaryKey();
        if (is_string($keys)) {
            $keys = [$keys];
        }
        foreach ($keys as $key) {
            if ($entity->isDirty($key)) {
                $entity->setError($key, ['key' => 'Cannot change primary key field']);
            }
        }
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew()) {
            return;
        }

        if (
            $entity->isDirty('modified') && $entity->isDirty('modifier_id') &&
            $entity->get('modifier_id') == $entity->getOriginal('modifier_id') &&
            count($entity->getDirty()) == 2
        ) {
            return;
        }

        TableRegistry::getTableLocator()->get('History')->logChange($entity);
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        TableRegistry::getTableLocator()->get('History')->logDeletion($entity);
    }

    public function getMaybe(mixed $id): ?EntityInterface
    {
        if (is_null($id)) {
            return null;
        }

        if (is_string($id)) {
            $id = (int)$id;
        }
        if (is_array($id)) {
            $id = [$id];
        }

        try {
            return $this->get($id);
        } catch (RecordNotFoundException $e) {
            return null;
        }
    }

    public function findWithContain(SelectQuery $query): SelectQuery
    {
        $contain = $this->contain();
        if (!empty($contain)) {
            $query->contain($contain);
        }

        return $query;
    }

    protected function belongsToManyThrough(string $associated, string $linktable): void
    {
        $this->belongsToMany($associated, ['through' => $linktable]);
    }

    protected function contain(): array
    {
        return [];
    }

    protected function orderBy(): array
    {
        return [];
    }

    protected function touchEntity(string $model, int $id): void
    {
        $table = TableRegistry::getTableLocator()->get($model);
        $entity = $table->get($id);

        if ($table->hasField('modified')) {
            $entity->set('modified', new DateTime());
        }
        if ($table->hasField('modifier_id')) {
            $who = Router::getRequest()->getAttribute('identity')->id;
            $entity->set('modifier_id', $who);
        }

        $table->save($entity);
    }
}
