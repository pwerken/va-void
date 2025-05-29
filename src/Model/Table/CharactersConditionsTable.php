<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;

class CharactersConditionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey(['character_id', 'condition_id']);

        $this->belongsTo('Characters');
        $this->belongsTo('Conditions');
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->character_id);
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->character_id);
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->isUnique(['condition_id', 'character_id']));

        $rules->add($rules->existsIn('character_id', 'Characters'));
        $rules->add($rules->existsIn('condition_id', 'Conditions'));

        return $rules;
    }

    protected function contain(): array
    {
        return ['Characters', 'Conditions'];
    }
}
