<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;

class CharactersPowersTable
    extends AppTable
{

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setPrimaryKey(['character_id', 'power_id']);

        $this->belongsTo('Characters');
        $this->belongsTo('Powers');
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
        $validator->notEmpty('power_id');
        $validator->allowEmpty('expiry');

        $validator->add('character_id', 'valid', ['rule' => 'numeric']);
        $validator->add('power_id', 'valid', ['rule' => 'numeric']);
        $validator->add('expiry', 'valid', ['rule' => 'date']);

        $validator->requirePresence('character_id', 'create');
        $validator->requirePresence('power_id', 'create');

        return $validator;
    }

    protected function contain(): array
    {
        return [ 'Characters', 'Powers' ];
    }
}
