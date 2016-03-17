<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CharactersPowersTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('characters_powers');
		$this->displayField('character_id');
		$this->primaryKey(['character_id', 'power_id']);
		$this->belongsTo('Characters');
		$this->belongsTo('Powers');
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
