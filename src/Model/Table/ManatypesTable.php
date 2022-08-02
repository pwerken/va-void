<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class ManatypesTable
    extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('Skills');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->allowEmpty('id', 'create');
        $validator->notEmpty('name');

        $validator->add('id', 'valid', ['rule' => 'numeric']);

        $validator->requirePresence('name', 'create');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoSkills']);
        return $rules;
    }

    public function ruleNoSkills($entity, $options)
    {
        $query = $this->Skills->find();
        $query->where(['manatype_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->errors('skills', 'reference(s) present');
            return false;
        }

        return true;
    }

    protected function contain(): array
    {
        return [ 'Skills' ];
    }

    protected function orderBy(): array
    {
        return  [ 'name' => 'ASC' ];
    }
}
