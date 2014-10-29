<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CharactersConditions Model
 */
class CharactersConditionsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('characters_conditions');
		$this->displayField('character_id');
		$this->primaryKey(['character_id', 'condition_id']);

		$this->belongsTo('Characters', [
			'foreignKey' => 'character_id',
		]);
		$this->belongsTo('Conditions', [
			'foreignKey' => 'condition_id',
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
			->add('condition_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('condition_id', 'create')
			->add('expiry', 'valid', ['rule' => 'date'])
			->allowEmpty('expiry');

		return $validator;
	}

}
