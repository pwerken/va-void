<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class SpellsTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->hasMany('CharactersSpells')->setProperty('characters');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addDelete([$this, 'ruleNoCharacters']);

        return $rules;
    }

    public function ruleNoCharacters($entity, $options)
    {
        $query = $this->CharactersSpells->find();
        $query->where(['spell_id' => $entity->id]);

        if($query->count() > 0) {
            $entity->setErrors('characters', $this->consistencyError);
            return false;
        }

        return true;
    }

    protected function orderBy(): array
    {
        return ['name' => 'ASC'];
    }
}
