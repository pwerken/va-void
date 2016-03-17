<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CharactersConditionsTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('characters_conditions');
		$this->displayField('character_id');
		$this->primaryKey(['character_id', 'condition_id']);
		$this->belongsTo('Characters');
		$this->belongsTo('Conditions');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->notEmpty('character_id');
		$validator->notEmpty('condition_id');
		$validaor->allowEmpty('expiry');

		$validaor->add('character_id', 'valid', ['rule' => 'numeric']);
		$validaor->add('condition_id', 'valid', ['rule' => 'numeric']);
		$validaor->add('expiry', 'valid', ['rule' => 'date']);

		$validator->requirePresence('character_id', 'create');
		$validator->requirePresence('condition_id', 'create');

		return $validator;
	}

}
