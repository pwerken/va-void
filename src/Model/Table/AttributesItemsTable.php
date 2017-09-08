<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Validation\Validator;

class AttributesItemsTable
	extends AppTable
{

	protected $_contain = [ 'Attributes', 'Items.Characters' ];

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->primaryKey(['attribute_id', 'item_id']);

		$this->belongsTo('Attributes');
		$this->belongsTo('Items');
	}

	public function afterDelete(Event $event, EntityInterface $entity, $options)
	{
		$this->touchEntity('Items', $entity->item_id);
	}

	public function afterSave(Event $event, EntityInterface $entity, $options)
	{
		$this->touchEntity('Items', $entity->item_id);
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
