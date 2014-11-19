<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Skills Model
 */
class SkillsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('skills');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->belongsTo('Manatypes');
		$this->belongsToMany('Characters');
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
			->add('cost', 'valid', ['rule' => 'numeric'])
			->requirePresence('cost', 'create')
			->notEmpty('cost')
			->add('manatype_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('manatype_id')
			->add('mana_amount', 'valid', ['rule' => 'numeric'])
			->allowEmpty('mana_amount')
			->add('sort_order', 'valid', ['rule' => 'numeric'])
			->allowEmpty('sort_order');

		return $validator;
	}

}
