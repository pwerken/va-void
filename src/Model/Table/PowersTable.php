<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Powers Model
 */
class PowersTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('powers');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');

		$this->belongsToMany('Characters', [
			'className' => 'CharactersTable',
			'foreignKey' => 'power_id',
			'targetForeignKey' => 'character_id',
			'joinTable' => 'characters_powers',
			'through' => 'CharactersPowers',
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
			->add('id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('id', 'create')
			->validatePresence('name', 'create')
			->notEmpty('name')
			->validatePresence('player_text', 'create')
			->notEmpty('player_text')
			->allowEmpty('cs_text');

		return $validator;
	}

}
