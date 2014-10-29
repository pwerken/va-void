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
		$this->displayField('name');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');

		$this->belongsTo('Players', [
			'foreignKey' => 'player_id',
		]);
		$this->belongsTo('Factions', [
			'foreignKey' => 'faction_id',
		]);
		$this->belongsTo('Believes', [
			'foreignKey' => 'belief_id',
		]);
		$this->belongsTo('Groups', [
			'foreignKey' => 'group_id',
		]);
		$this->belongsTo('Worlds', [
			'foreignKey' => 'world_id',
		]);
		$this->hasMany('Items', [
			'foreignKey' => 'character_id',
		]);
		$this->belongsToMany('Conditions', [
			'foreignKey' => 'character_id',
			'targetForeignKey' => 'condition_id',
			'joinTable' => 'characters_conditions',
		]);
		$this->belongsToMany('Powers', [
			'foreignKey' => 'character_id',
			'targetForeignKey' => 'power_id',
			'joinTable' => 'characters_powers',
		]);
		$this->belongsToMany('Skills', [
			'foreignKey' => 'character_id',
			'targetForeignKey' => 'skill_id',
			'joinTable' => 'characters_skills',
		]);
		$this->belongsToMany('Spells', [
			'foreignKey' => 'character_id',
			'targetForeignKey' => 'spell_id',
			'joinTable' => 'characters_spells',
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
			->add('player_id', 'valid', ['rule' => 'numeric'])
			->validatePresence('player_id', 'create')
			->notEmpty('player_id')
			->add('chin', 'valid', ['rule' => 'numeric'])
			->validatePresence('chin', 'create')
			->notEmpty('chin')
			->validatePresence('name', 'create')
			->notEmpty('name')
			->add('xp', 'valid', ['rule' => 'decimal'])
			->validatePresence('xp', 'create')
			->notEmpty('xp')
			->add('faction_id', 'valid', ['rule' => 'numeric'])
			->validatePresence('faction_id', 'create')
			->notEmpty('faction_id')
			->add('belief_id', 'valid', ['rule' => 'numeric'])
			->validatePresence('belief_id', 'create')
			->notEmpty('belief_id')
			->add('group_id', 'valid', ['rule' => 'numeric'])
			->validatePresence('group_id', 'create')
			->notEmpty('group_id')
			->add('world_id', 'valid', ['rule' => 'numeric'])
			->validatePresence('world_id', 'create')
			->notEmpty('world_id')
			->allowEmpty('status')
			->allowEmpty('comments');

		return $validator;
	}

}
