<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CharactersSpells Model
 */
class CharactersSpellsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('characters_spells');
		$this->displayField('character_id');
		$this->primaryKey(['character_id', 'spell_id']);

		$this->belongsTo('Characters', [
			'foreignKey' => 'character_id',
		]);
		$this->belongsTo('Spells', [
			'foreignKey' => 'spell_id',
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
			->add('spell_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('spell_id', 'create')
			->add('level', 'valid', ['rule' => 'numeric'])
			->validatePresence('level', 'create')
			->notEmpty('level');

		return $validator;
	}

}
