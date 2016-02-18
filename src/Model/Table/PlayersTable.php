<?php
namespace App\Model\Table;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\Player;

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
		$this->displayField('displayName');
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
			->notEmpty('id')
			->add('account_type', 'valid', ['rule' => ['inList', Player::labelsAccountTypes(true)]] )
			->allowEmpty('password')
			->notEmpty('first_name')
			->allowEmpty('insertion')
			->notEmpty('last_name')
			->add('gender', 'valid', ['rule' => ['inList', Player::labelsGenders(true)]])
			->allowEmpty('gender')
			->add('date_of_birth', 'valid', ['rule' => 'date'])
			->allowEmpty('date_of_birth');

		return $validator;
	}

}
