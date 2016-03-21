<?php
namespace App\Model\Table;

use Cake\Validation\Validator;

class AttributesItemsTable
	extends AppTable
{

	protected $_contain = [ 'Attributes', 'Items' => [ 'Characters' ] ];

	public function initialize(array $config)
	{
		$this->table('attributes_items');
		$this->displayField('attribute_id');
		$this->primaryKey(['attribute_id', 'item_id']);
		$this->belongsTo('Attributes');
		$this->belongsTo('Items');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->notEmpty('attribute_id');
		$validator->notEmpty('item_id');

		$validator->add('attribute_id', 'valid', ['rule' => 'numeric']);
		$validator->add('item_id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('attribute_id', 'create');
		$validator->requirePresence('item_id', 'create');

		return $validator;
	}

}
