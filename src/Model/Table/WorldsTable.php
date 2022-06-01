<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class WorldsTable
	extends AppTable
{

	public function initialize(array $config): void
	{
		parent::initialize($config);

		$this->hasMany('Characters');
	}

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
		$rules->addDelete([$this, 'ruleNoCharacters']);
		return $rules;
	}

	public function ruleNoCharacters($entity, $options)
	{
		$query = $this->Characters->find();
		$query->where(['world_id' => $entity->id]);
		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}
		return true;
	}

	protected function contain(): array
	{
		return [ 'Characters' ];
	}

	protected function orderBy(): array
	{
		return	[ 'name' => 'ASC' ];
	}
}
