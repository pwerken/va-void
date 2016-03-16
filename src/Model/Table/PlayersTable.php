<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuthState;
use App\Model\Entity\Player;

/**
 * Players Model
 */
class PlayersTable
	extends Table
{

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
	public function validationDefault(Validator $validator)
	{
		return $validator
			->add('id', 'valid', ['rule' => 'numeric'])
			->notEmpty('id')
			->add('role', 'valid', ['rule' => ['inList', Player::labelsRoles(true)]] )
			->allowEmpty('password')
			->notEmpty('first_name')
			->allowEmpty('insertion')
			->notEmpty('last_name')
			->add('gender', 'valid', ['rule' => ['inList', Player::labelsGenders(true)]])
			->allowEmpty('gender')
			->add('date_of_birth', 'valid', ['rule' => 'date'])
			->allowEmpty('date_of_birth');
	}

	public function buildRules(RulesChecker $rules)
	{
		return $rules
			->add([$this, 'checkRoleRule'], ['errorField' => 'role']);
	}

	public function checkRoleRule($entity, $options)
	{
		if(!$entity->dirty('role') || AuthState::hasRole('super'))
			return true;

		// don't demote someone who is above your auth level
		if(!AuthState::hasRole($entity->getOriginal('role')))
			return "Cannot demote user that has more permissions than you.";

		// don't promote someone to above your auth level
		if(!AuthState::hasRole($entity->get('role')))
			return "Cannot promote user to more permissions than you have.";

		return true;
	}

}
