<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\AuthState;
use App\Model\Entity\Player;

class PlayersTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('players');
		$this->displayField('displayName');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->hasMany('Characters');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->notEmpty('id');
		$validator->allowEmpty('password');
		$validator->notEmpty('first_name');
		$validator->allowEmpty('insertion');
		$validator->notEmpty('last_name');
		$validator->allowEmpty('gender');
		$validator->allowEmpty('date_of_birth');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('role', 'valid', ['rule' => ['inList', Player::labelsRoles(true)]] );
		$validator->add('gender', 'valid', ['rule' => ['inList', Player::labelsGenders(true)]]);
		$validator->add('date_of_birth', 'valid', ['rule' => 'date']);

		return $validator;
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
