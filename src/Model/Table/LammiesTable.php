<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;

class LammiesTable
	extends AppTable
{

	public function initialize(array $config)
	{
		$this->table('lammies');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('entity');
		$validator->notEmpty('key1');
		$validator->allowEmpty('key2');
		$validator->notEmpty('printed');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('key1', 'valid', ['rule' => 'numeric']);
		$validator->add('key2', 'valid', ['rule' => 'numeric']);
		$validator->add('printed', 'valid', ['rule' => 'boolean']);

		$validator->requirePresence('entity', 'create');
		$validator->requirePresence('key1', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->add([$this, 'ruleEntityExists']);
		return $rules;
	}

	public function ruleEntityExists($entity, $options)
	{
		$class = 'App\\Model\\Entity\\'.$entity->entity;
		if(!class_exists($class)) {
			$entity->errors('entity', 'unknown entity type');
			return false;
		}

		if(is_null($entity->target)) {
			$entity->errors('key1', 'no such entity');
			return false;
		}

		return true;
	}

}
