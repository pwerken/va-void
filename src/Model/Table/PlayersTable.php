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
		$rules->add([$this, 'ruleRoleChange']);
		$rules->addDelete([$this, 'ruleNoCharacters']);
		return $rules;
	}

	public function ruleRoleChange($entity, $options)
	{
		if(!$entity->dirty('role') || AuthState::hasRole('super'))
			return true;

		$msg = true;

		// don't demote someone who is above your auth level
		if(!AuthState::hasRole($entity->getOriginal('role')))
			$msg =  "Cannot demote user that has more permissions than you.";

		// don't promote someone to above your auth level
		if(!AuthState::hasRole($entity->get('role')))
			$msg = "Cannot promote user to more permissions than you have.";

		if($msg !== true) {
			$entity->errors('role', $msg);
			return false;
		}

		return true;
	}

	public function ruleNoCharacters($entity, $options)
	{
		$query = $this->Characters->find();
		$query->where(['player_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}

		return true;
	}

}
