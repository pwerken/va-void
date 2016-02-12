<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Characters Model
 */
class CharactersTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
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
			->add('player_id', 'valid', ['rule' => 'numeric'])
			->requirePresence('player_id', 'create')
			->notEmpty('player_id')
			->add('chin', 'valid', ['rule' => 'naturalNumber'])
			->requirePresence('chin', 'create')
			->notEmpty('chin')
			->requirePresence('name', 'create')
			->notEmpty('name')
			->add('xp', 'valid', ['rule' => ['custom', '/^[0-9]*([\.][05])?$/']])
			->add('xp', 'range', ['rule' => ['range', 0, 50]])
			->notEmpty('xp')
			->add('faction_id', 'valid', ['rule' => 'numeric'])
			->requirePresence('faction_id', 'create')
			->notEmpty('faction_id')
			->add('belief_id', 'valid', ['rule' => 'numeric'])
			->requirePresence('belief_id', 'create')
			->notEmpty('belief_id')
			->add('group_id', 'valid', ['rule' => 'numeric'])
			->requirePresence('group_id', 'create')
			->notEmpty('group_id')
			->add('world_id', 'valid', ['rule' => 'numeric'])
			->requirePresence('world_id', 'create')
			->notEmpty('world_id')
			->allowEmpty('status')
			->allowEmpty('comments');

		return $validator;
	}

	public function plinChin($plin, $chin) {
		return $this->findByPlayerIdAndChin($plin, $chin)->firstOrFail();
	}

}
