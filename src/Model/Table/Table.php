<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Database\Type\EnumType;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table as CakeTable;
use Cake\Routing\Router;

abstract class Table extends CakeTable
{
    use LocatorAwareTrait;

    protected bool $allowSetPrimaryOnCreate = false;

    protected array $consistencyError = ['consistency' => 'Reference(s) present'];

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('WhoWhen');

        $class = getShortClassName($this->getEntityClass());
        $this->_validatorClass = "App\\Model\\Validation\\{$class}Validator";
    }

    public function afterRules(
        EventInterface $event,
        EntityInterface $entity,
        ArrayObject $options,
        bool $result,
        string $operation,
    ): void {
        // CakePHP 5.2.7 no longer supports invisible validation rules, it adds
        // '_rule' to prevent silent failures.  Our validation rules are
        // intentionally invisible, they call setErrors on the entity directly.
        $entity->setDirty('_rule');
        $entity->setDirty('_rule', false);
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

        $this->fetchTable('History')->logChange($entity);
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->fetchTable('History')->logDeletion($entity);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        if (!$this->allowSetPrimaryOnCreate) {
            $rules->addCreate([$this, 'ruleDisallowSetPrimary']);
        }
        $rules->addUpdate([$this, 'ruleDisallowSetPrimary']);

        return $rules;
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

    public function findAll(SelectQuery $query): SelectQuery
    {
        $order = [];
        foreach ($this->orderBy() as $field => $ord) {
            $order[$this->aliasField($field)] = $ord;
        }

        return parent::findAll($query)->orderBy($order);
    }

    public function findIndex(SelectQuery $query): SelectQuery
    {
        return $this->findAll($query);
    }

    public function findWithContain(SelectQuery $query): SelectQuery
    {
        $contain = $this->contain();
        if (!empty($contain)) {
            $query->contain($contain);
        }

        return $this->findAll($query);
    }

    public function ruleDisallowSetPrimary(EntityInterface $entity, array $options): bool
    {
        $keys = $this->getPrimaryKey();
        if (is_string($keys)) {
            $keys = [$keys];
        }

        $allowed = true;
        foreach ($keys as $key) {
            if ($entity->isDirty($key)) {
                $entity->setError($key, ['key' => 'Cannot change primary key field']);
                $allowed = false;
            }
        }

        return $allowed;
    }

    public function ruleNoAssociation(EntityInterface $entity, array $options): bool
    {
        $field = $options[0];
        $value = $entity->get($field);

        if (!is_null($value) && count($value) > 0) {
            $entity->setError($field, $this->consistencyError);

            return false;
        }

        return true;
    }

    public function ruleManaConsistency(EntityInterface $entity, array $options): bool
    {
        $amount = $entity->get('mana_amount');
        $manatype = $entity->get('manatype_id');

        if ($amount > 0 && is_null($manatype)) {
            $entity->setError('manatype_id', ['consistency' => 'Missing value']);

            return false;
        }

        if ($amount == 0 && $manatype) {
            $entity->setError('mana_amount', ['consistency' => 'Missing value']);

            return false;
        }

        return true;
    }

    protected function setColumnEnumType(string $column, string $enumClass): void
    {
        $this->getSchema()->setColumnType($column, EnumType::from($enumClass));
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
        $table = $this->fetchTable($model);
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
