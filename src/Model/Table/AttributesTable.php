<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class AttributesTable
    extends AppTable
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

    public function ruleNoItems($entity, $options)
    {
        $query = $this->AttributesItems->find();
        $query->where(['attributes_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->errors('items', 'reference(s) present');
            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return [ 'AttributesItems.Items' ];
    }

    protected function orderBy(): array
    {
        return  [ 'name' => 'ASC', 'id' => 'ASC' ];
    }
}
