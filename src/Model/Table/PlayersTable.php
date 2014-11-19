<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Players Model
 */
class PlayersTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('players');
		$this->displayField('id');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->hasMany('Characters');
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
			->requirePresence('account_type', 'create')
			->notEmpty('account_type')
			->allowEmpty('username')
			->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
			->allowEmpty('password')
			->allowEmpty('first_name')
			->allowEmpty('insertion')
			->allowEmpty('last_name')
			->allowEmpty('gender')
			->add('date_of_birth', 'valid', ['rule' => 'date'])
			->allowEmpty('date_of_birth');

		return $validator;
	}

}
