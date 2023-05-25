<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class SkillsTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsTo('Manatypes');
        $this->hasMany('CharactersSkills')->setProperty('characters');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoCharacters']);
        return $rules;
    }

    public function ruleNoCharacters($entity, $options)
    {
        $query = $this->CharactersSkills->find();
        $query->where(['skill_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->errors('characters', 'reference(s) present');
            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return [ 'Manatypes' ];
    }

    protected function orderBy(): array
    {
        return  [ 'sort_order' => 'ASC', 'name' => 'ASC' ];
    }
}
