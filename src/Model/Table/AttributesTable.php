<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;

/**
 * @property \App\Model\Table\AttributesItemsTable $AttributesItems;
 */
class AttributesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('AttributesItems')->setProperty('items');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoItems']);

        return $rules;
    }

    public function ruleNoItems(EntityInterface $entity, array $options): bool
    {
        $query = $this->AttributesItems->find();
        $query->where(['attributes_id' => $entity->id]);

        if ($query->count() > 0) {
            $entity->setError('items', $this->consistencyError);

            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return ['AttributesItems.Items'];
    }

    protected function orderBy(): array
    {
        return ['name' => 'ASC', 'id' => 'ASC'];
    }
}
