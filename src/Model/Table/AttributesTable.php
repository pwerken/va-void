<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class AttributesTable
	extends AppTable
{

	public function initialize(array $config): void
	{
		parent::initialize($config);

		$this->hasMany('AttributesItems')->setProperty('items');
	}

	public function validationDefault(Validator $validator): Validator
	{
		$validator->allowEmpty('id', 'create');
		$validator->allowEmpty('name');
		$validator->allowEmpty('category');
		$validator->notEmpty('code');

		$validator->add('id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('code', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules): RulesChecker
	{
		$rules->addDelete([$this, 'ruleNoItems']);
		return $rules;
	}

	public function ruleNoItems($entity, $options)
	{
		$query = $this->AttributesItems->find();
		$query->where(['attributes_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('items', 'reference(s) present');
			return false;
		}

		return true;
	}

	protected function contain(): array
	{
		return [ 'AttributesItems.Items' ];
	}

	protected function orderBy(): array
	{
		return	[ 'name' => 'ASC', 'id' => 'ASC' ];
	}
}
