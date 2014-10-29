<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AttributesItems Model
 */
class AttributesItemsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('attributes_items');
		$this->displayField('attribute_id');
		$this->primaryKey(['attribute_id', 'item_id']);

		$this->belongsTo('Attributes', [
			'foreignKey' => 'attribute_id',
		]);
		$this->belongsTo('Items', [
			'foreignKey' => 'item_id',
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
			->add('attribute_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('attribute_id', 'create')
			->add('item_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('item_id', 'create');

		return $validator;
	}

}
