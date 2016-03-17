<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class FactionsTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('factions');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->hasMany('Characters');
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
		$rules->addDelete([$this, 'ruleNoCharacters']);
		return $rules;
	}

	public function ruleNoCharacters($entity, $options)
	{
		$query = $this->Characters->find();
		$query->where(['faction_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}

		return true;
	}

}
