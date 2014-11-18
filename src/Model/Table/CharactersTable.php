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
			'alias' => 'Players',
			'foreignKey' => 'player_id'
		]);
		$this->belongsTo('Factions', [
			'alias' => 'Factions',
			'foreignKey' => 'faction_id'
		]);
		$this->belongsTo('Believes', [
			'alias' => 'Believes',
			'foreignKey' => 'belief_id'
		]);
		$this->belongsTo('Groups', [
			'alias' => 'Groups',
			'foreignKey' => 'group_id'
		]);
		$this->belongsTo('Worlds', [
			'alias' => 'Worlds',
			'foreignKey' => 'world_id'
		]);
		$this->hasMany('Items', [
			'alias' => 'Items',
			'foreignKey' => 'character_id'
		]);
		$this->belongsToMany('Conditions', [
			'alias' => 'Conditions',
			'className' => 'ConditonsTable',
			'foreignKey' => 'character_id',
			'targetForeignKey' => 'condition_id',
			'joinTable' => 'characters_conditions',
			'through' => 'CharactersConditions'
		]);
		$this->belongsToMany('Powers', [
			'alias' => 'Powers',
			'className' => 'PowersTable',
			'foreignKey' => 'character_id',
			'targetForeignKey' => 'power_id',
			'joinTable' => 'characters_powers',
			'through' => 'CharactersPowers'
		]);
		$this->belongsToMany('Skills', [
			'alias' => 'Skills',
			'foreignKey' => 'character_id',
			'targetForeignKey' => 'skill_id',
			'joinTable' => 'characters_skills'
		]);
		$this->belongsToMany('Spells', [
			'alias' => 'Spells',
			'className' => 'SpellsTable',
			'foreignKey' => 'character_id',
			'targetForeignKey' => 'spell_id',
			'joinTable' => 'characters_spells',
			'through' => 'CharactersSpells'
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
			->requirePresence('player_id', 'create')
			->notEmpty('player_id')
			->add('chin', 'valid', ['rule' => 'numeric'])
			->requirePresence('chin', 'create')
			->notEmpty('chin')
			->requirePresence('name', 'create')
			->notEmpty('name')
			->add('xp', 'valid', ['rule' => 'decimal'])
			->requirePresence('xp', 'create')
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

}
