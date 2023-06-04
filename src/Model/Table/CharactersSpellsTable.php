<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;

class CharactersSpellsTable
    extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey(['character_id', 'spell_id']);

        $this->belongsTo('Characters');
        $this->belongsTo('Spells');
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
        $rules->addCreate($rules->isUnique(['spell_id', 'character_id']));

        $rules->add($rules->existsIn('character_id', 'Characters'));
        $rules->add($rules->existsIn('spell_id', 'Spells'));

        return $rules;
    }

    protected function contain(): array
    {
        return ['Characters', 'Spells'];
    }

    protected function orderBy(): array
    {
        return ['level' => 'DESC'];
    }
}
