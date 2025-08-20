<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;

class CharactersPowersTable extends Table
{
    protected bool $allowSetPrimaryOnCreate = true;

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey(['character_id', 'power_id']);

        $this->belongsTo('Characters');
        $this->belongsTo('Powers');
    }

    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('character_id'));
    }

    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $this->touchEntity('Characters', $entity->get('character_id'));
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->isUnique(['power_id', 'character_id']));

        $rules->add($rules->existsIn('character_id', 'Characters'));
        $rules->add($rules->existsIn('power_id', 'Powers'));

        return $rules;
    }

    protected function contain(): array
    {
        return ['Characters', 'Powers'];
    }
}
