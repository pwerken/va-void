<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CharactersTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('characters');
		$this->displayField('displayName');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('Players');
		$this->belongsTo('Factions');
		$this->belongsTo('Believes');
		$this->belongsTo('Groups');
		$this->belongsTo('Worlds');
		$this->hasMany('Items');
		$this->belongsToMany('Conditions');
		$this->belongsToMany('Powers');
		$this->belongsToMany('Skills');
		$this->belongsToMany('Spells');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('player_id');
		$validator->notEmpty('chin');
		$validator->notEmpty('name');
		$validator->notEmpty('xp');
		$validator->notEmpty('faction_id');
		$validator->notEmpty('belief_id');
		$validator->notEmpty('group_id');
		$validator->notEmpty('world_id');
		$validator->allowEmpty('status');
		$validator->allowEmpty('comments');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('player_id', 'valid', ['rule' => 'numeric']);
		$validator->add('chin', 'valid', ['rule' => 'naturalNumber']);
		$validator->add('xp', 'valid', ['rule' => ['custom', '/^[0-9]*([\.][05])?$/']]);
		$validator->add('faction_id', 'valid', ['rule' => 'numeric']);
		$validator->add('belief_id', 'valid', ['rule' => 'numeric']);
		$validator->add('group_id', 'valid', ['rule' => 'numeric']);
		$validator->add('world_id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('player_id', 'create');
		$validator->requirePresence('chin', 'create');
		$validator->requirePresence('name', 'create');

		return $validator;
	}

	public function plinChin($plin, $chin)
	{
		return $this->findByPlayerIdAndChin($plin, $chin)->firstOrFail();
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->existsIn('faction_id', 'factions'));
		$rules->add($rules->existsIn('group_id', 'groups'));
		$rules->add($rules->existsIn('belief_id', 'believes'));
		$rules->add($rules->existsIn('world_id', 'worlds'));
		return $rules;
	}
}
