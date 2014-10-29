<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CharactersSkills Model
 */
class CharactersSkillsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('characters_skills');
		$this->displayField('character_id');
		$this->primaryKey(['character_id', 'skill_id']);

		$this->belongsTo('Characters', [
			'foreignKey' => 'character_id',
		]);
		$this->belongsTo('Skills', [
			'foreignKey' => 'skill_id',
		]);
	}

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('character_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('character_id', 'create')
			->add('skill_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('skill_id', 'create');

		return $validator;
	}

}
