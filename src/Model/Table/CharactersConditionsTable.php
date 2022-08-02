<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;

class CharactersConditionsTable
    extends AppTable
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

    public function validationDefault(Validator $validator): Validator
    {
        $validator->notEmpty('character_id');
        $validator->notEmpty('condition_id');
        $validator->allowEmpty('expiry');

        $validator->add('character_id', 'valid', ['rule' => 'numeric']);
        $validator->add('condition_id', 'valid', ['rule' => 'numeric']);
        $validator->add('expiry', 'valid', ['rule' => 'date']);

        $validator->requirePresence('character_id', 'create');
        $validator->requirePresence('condition_id', 'create');

        return $validator;
    }

    protected function contain(): array
    {
        return [ 'Characters', 'Conditions' ];
    }
}
