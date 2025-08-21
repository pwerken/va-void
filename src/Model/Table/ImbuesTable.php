<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class ImbuesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->belongsTo('Manatypes');

        $this->belongsToManyThrough('Characters', 'CharactersImbues');
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addDelete([$this, 'ruleNoAssociation'], ['characters', 'Characters']);

        $rules->add($rules->existsIn('manatype_id', 'Manatypes'));
        $rules->add([$this, 'ruleManaConsistency']);

        return $rules;
    }

    protected function contain(): array
    {
        return ['Characters'];
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}
