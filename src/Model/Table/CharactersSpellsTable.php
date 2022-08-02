<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;

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

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmpty('character_id');
        $validator->notEmpty('spell_id');
        $validator->notEmpty('level');

        $validator->add('character_id', 'valid', ['rule' => 'numeric']);
        $validator->add('spell_id', 'valid', ['rule' => 'numeric']);
        $validator->add('level', 'valid', ['rule' => ['inList', [1,2,3]]]);

        $validator->requirePresence('character_id', 'create');
        $validator->requirePresence('spell_id', 'create');
        $validator->requirePresence('level', 'create');

        return $validator;
    }

    protected function contain(): array
    {
        return [ 'Characters', 'Spells' ];
    }

    protected function orderBy(): array
    {
        return [ 'level' => 'DESC' ];
    }
}
