<?php
namespace App\Model\Entity;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class Audit
	extends Entity
{

	public static function fromEntity(EntityInterface $entity)
	{
		if(is_null($entity))
			return NULL;

		$table = TableRegistry::get($entity->source());
		$columns = $table->getSchema()->columns();
		$data = $entity->extractOriginal($columns);

		$primary = $table->primaryKey();
		if(!is_array($primary))
			$primary = [$primary];

		$audit = new Audit();
		$audit->set('entity', $entity->getClass());
		foreach($primary as $key => $field) {
			$audit->set("key".($key+1), $data[$field]);
			unset($data[$field]);
		}
		$audit->set('modified', $data['modified']);
		$audit->set('modifier_id', $data['modifier_id']);
		unset($data['modified']);
		unset($data['modifier_id']);

		if(empty($data)) {
			$audit->set('data', '{}');
		} else {
			$audit->set('data', json_encode($data));
		}
		return $audit;
	}
}
