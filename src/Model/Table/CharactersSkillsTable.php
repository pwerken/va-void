<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CharactersSkillsTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('characters_skills');
		$this->displayField('character_id');
		$this->primaryKey(['character_id', 'skill_id']);
		$this->belongsTo('Characters');
		$this->belongsTo('Skills');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->notEmpty('character_id');
		$validator->notEmpty('skill_id');

		$validator->add('character_id', 'valid', ['rule' => 'numeric']);
		$validator->add('skill_id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('character_id', 'create');
		$validator->requirePresence('skill_id', 'create');

		return $validator;
	}

}
