<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class SkillsTable
    extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsTo('Manatypes');
        $this->hasMany('CharactersSkills')->setProperty('characters');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->allowEmpty('id', 'create');
        $validator->notEmpty('name');
        $validator->notEmpty('cost');
        $validator->allowEmpty('manatype_id');
        $validator->allowEmpty('mana_amount');
        $validator->allowEmpty('sort_order');

        $validator->add('id', 'valid', ['rule' => 'numeric']);
        $validator->add('cost', 'valid', ['rule' => 'numeric']);
        $validator->add('manatype_id', 'valid', ['rule' => 'numeric']);
        $validator->add('mana_amount', 'valid', ['rule' => 'numeric']);
        $validator->add('sort_order', 'valid', ['rule' => 'numeric']);

        $validator->requirePresence('name', 'create');
        $validator->requirePresence('cost', 'create');

        return $validator;
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
