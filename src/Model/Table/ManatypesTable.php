<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class ManatypesTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('Skills');
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
            $entity->setError('skills', $this->consistencyError);
            return false;
        }

        return true;
    }

    protected function orderBy(): array
    {
        return ['name' => 'ASC'];
    }
}
