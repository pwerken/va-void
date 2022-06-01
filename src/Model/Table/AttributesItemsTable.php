<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Validation\Validator;

class AttributesItemsTable
	extends AppTable
{

	public function initialize(array $config): void
	{
		parent::initialize($config);

		$this->setPrimaryKey(['attribute_id', 'item_id']);

		$this->belongsTo('Attributes');
		$this->belongsTo('Items');
	}

	public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
	{
		$this->touchEntity('Items', $entity->item_id);
	}

	public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
	{
		$this->touchEntity('Items', $entity->item_id);
	}

	public function validationDefault(Validator $validator): Validator
	{
		$validator->notEmpty('attribute_id');
		$validator->notEmpty('item_id');

		$validator->add('attribute_id', 'valid', ['rule' => 'numeric']);
		$validator->add('item_id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('attribute_id', 'create');
		$validator->requirePresence('item_id', 'create');

		return $validator;
	}

	protected function contain(): array
	{
		return [ 'Attributes', 'Items.Characters' ];
	}
}
