<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class FactionsTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('Characters');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name'], 'This name is already in use.'));
        $rules->addDelete([$this, 'ruleNoCharacters']);
        return $rules;
    }

    public function ruleNoCharacters($entity, $options)
    {
        $query = $this->Characters->find();
        $query->where(['faction_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->errors('characters', 'reference(s) present');
            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return [ 'Characters' ];
    }

    protected function orderBy(): array
    {
        return  [ 'name' => 'ASC' ];
    }
}
