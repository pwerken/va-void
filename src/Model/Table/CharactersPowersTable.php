<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Validation\Validator;

class CharactersPowersTable
	extends AppTable
{

	protected $_contain = [ 'Characters', 'Powers' ];

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->table('characters_powers');
		$this->primaryKey(['character_id', 'power_id']);
		$this->belongsTo('Characters');
		$this->belongsTo('Powers');
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
		$validator->notEmpty('power_id');
		$validator->allowEmpty('expiry');

		$validator->add('character_id', 'valid', ['rule' => 'numeric']);
		$validator->add('power_id', 'valid', ['rule' => 'numeric']);
		$validator->add('expiry', 'valid', ['rule' => 'date']);

		$validator->requirePresence('character_id', 'create');
		$validator->requirePresence('power_id', 'create');

		return $validator;
	}

}
