<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class EventsTable
	extends AppTable
{

	public function validationDefault(Validator $validator): Validator
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');

		$validator->add('id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('name', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules): RulesChecker
	{
		$rules->add($rules->isUnique(['name'], 'This name is already in use.'));
		return $rules;
	}

	protected function orderBy(): array
	{
		return	[ 'id' => 'ASC' ];
	}
}
