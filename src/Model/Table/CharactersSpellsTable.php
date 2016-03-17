<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CharactersSpellsTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('characters_spells');
		$this->displayField('character_id');
		$this->primaryKey(['character_id', 'spell_id']);
		$this->belongsTo('Characters');
		$this->belongsTo('Spells');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->notEmpty('character_id');
		$validator->notEmpty('spell_id');
		$validator->notEmpty('level');

		$validator->add('character_id', 'valid', ['rule' => 'numeric']);
		$validator->add('spell_id', 'valid', ['rule' => 'numeric']);
		$validator->add('level', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('character_id', 'create');
		$validator->requirePresence('spell_id', 'create');
		$validator->requirePresence('level', 'create');

		return $validator;
	}

}
