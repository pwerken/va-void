<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Validation\Validator;

class CharactersSpellsTable
	extends AppTable
{

	protected $_contain = [ 'Characters', 'Spells' ];

	public function initialize(array $config)
	{
		$this->table('characters_spells');
		$this->primaryKey(['character_id', 'spell_id']);
		$this->belongsTo('Characters');
		$this->belongsTo('Spells');
	}

	public function afterDelete(Event $event, EntityInterface $entity, $options)
	{
		$this->touchEntity('Characters', $entity->character_id);
	}

	public function afterSave(Event $event, EntityInterface $entity, $options)
	{
		$this->touchEntity('Characters', $entity->character_id);
	}

	public function orderBy()
	{
		return [ 'level' => 'DESC' ];
	}

	public function validationDefault(Validator $validator)
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

}
