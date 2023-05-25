<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class ConditionsTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('CharactersConditions')->setProperty('characters');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoCharacters']);
        return $rules;
    }

    public function ruleNoCharacters($entity, $options)
    {
        $query = $this->CharactersConditions->find();
        $query->where(['condition_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->errors('characters', 'reference(s) present');
            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return [ 'CharactersConditions.Characters' ];
    }

    protected function orderBy(): array
    {
        return  [ 'id' => 'ASC' ];
    }
}
