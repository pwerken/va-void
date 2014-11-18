<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Items Model
 */
class ItemsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('items');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('Characters', [
			'alias' => 'Characters',
			'foreignKey' => 'character_id'
		]);
		$this->belongsToMany('Attributes', [
			'alias' => 'Attributes',
			'foreignKey' => 'item_id',
			'targetForeignKey' => 'attribute_id',
			'joinTable' => 'attributes_items'
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
			->allowEmpty('description')
			->allowEmpty('player_text')
			->allowEmpty('cs_text')
			->add('character_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('character_id')
			->add('expiry', 'valid', ['rule' => 'date'])
			->allowEmpty('expiry');

		return $validator;
	}

}
