<?php
namespace App\Model\Table;

use App\Model\Entity\Audit;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class AuditsTable
	extends AppTable
{

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->table('audits');
		$this->primaryKey('id');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('entity');
		$validator->notEmpty('key1');
		$validator->allowEmpty('key2');
		$validator->allowEmpty('data');
		$validator->allowEmpty('modified');
		$validator->allowEmpty('modifier_id');

		$validator->add('id',          'valid', ['rule' => 'numeric']);
		$validator->add('key1',        'valid', ['rule' => 'numeric']);
		$validator->add('key2',        'valid', ['rule' => 'numeric']);
		$validator->add('modifier_id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('entity', 'create');
		$validator->requirePresence('key1',   'create');

		return $validator;
	}

	public function beforeDelete(Event $event, EntityInterface $entity, $options)
	{
	}

	public function beforeSave(Event $event, EntityInterface $entity, $options)
	{
	}

	public function logDeletion(EntityInterface $entity)
	{
		$lastChange = $this->logChange($entity);

		$audit = $this->newEntity();
		$audit->set('entity', $lastChange->get('entity'));
		$audit->set('key1', $lastChange->get('key1'));
		$audit->set('key2', $lastChange->get('key2'));
		$audit->set('data', NULL);

		$this->save($audit);

		return $audit;
	}

	public function logChange(EntityInterface $entity)
	{
		if($entity->dirty('modified') && count($entity->getDirty()) == 1) {
			return;
		}

		$audit = Audit::fromEntity($entity);
		$this->save($audit);
		return $audit;
	}
}
