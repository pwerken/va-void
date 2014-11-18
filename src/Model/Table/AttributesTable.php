<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Attributes Model
 */
class AttributesTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('attributes');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->belongsToMany('Items', [
			'alias' => 'Items',
			'foreignKey' => 'attribute_id',
			'targetForeignKey' => 'item_id',
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
			->allowEmpty('name')
			->allowEmpty('lorType')
			->requirePresence('code', 'create')
			->notEmpty('code');

		return $validator;
	}

}
