<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Validation\Validator;

class CharactersConditionsTable
	extends AppTable
{

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->primaryKey(['character_id', 'condition_id']);

		$this->belongsTo('Characters');
		$this->belongsTo('Conditions');
	}

	public function afterDelete(Event $event, EntityInterface $entity, $options)
	{
		$this->touchEntity('Characters', $entity->character_id);
	}

	public function afterSave(Event $event, EntityInterface $entity, $options)
	{
		$this->touchEntity('Characters', $entity->character_id);
	}

	public function validationDefault(Validator $validator)
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

	protected function contain()
	{
		return [ 'Characters', 'Conditions' ];
	}
}
