<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class EventsTable
	extends AppTable
{
	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->table('events');
		$this->primaryKey('id');
	}

	public function orderBy()
	{
		return	[ 'id' => 'ASC' ];
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');

		$validator->add('id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('name', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->isUnique(['name'], 'This name is already in use.'));
		return $rules;
	}
}
