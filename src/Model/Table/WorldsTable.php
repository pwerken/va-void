<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class WorldsTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('Characters');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name']));

        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleNoCharacters($entity, $options)
    {
        $query = $this->Characters->find();
        $query->where(['world_id' => $entity->id]);
        if($query->count() > 0) {
            $entity->setError('characters', $this->consistencyError);
            return false;
        }
        return true;
    }

    protected function contain(): array
    {
        return ['Characters'];
    }

    protected function orderBy(): array
    {
        return ['name' => 'ASC'];
    }
}
