<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Spells Model
 */
class SpellsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('spells');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->belongsToMany('Characters', [
			'alias' => 'Characters',
			'className' => 'CharactersTable',
			'foreignKey' => 'spell_id',
			'targetForeignKey' => 'character_id',
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
			->requirePresence('name', 'create')
			->notEmpty('name')
			->requirePresence('short', 'create')
			->notEmpty('short')
			->add('spiritual', 'valid', ['rule' => 'boolean'])
			->requirePresence('spiritual', 'create')
			->notEmpty('spiritual');

		return $validator;
	}

}
