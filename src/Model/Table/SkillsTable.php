<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class SkillsTable
	extends AppTable
{

	protected $_contain =
		[ 'Manatypes'
		];

	public function initialize(array $config)
	{
		$this->table('skills');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->belongsTo('Manatypes');
		$this->hasMany('CharactersSkills')->setProperty('characters');
	}

	public function orderBy()
	{
		return	[ 'sort_order' => 'ASC', 'name' => 'ASC' ];
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');
		$validator->notEmpty('cost');
		$validator->allowEmpty('manatype_id');
		$validator->allowEmpty('mana_amount');
		$validator->allowEmpty('sort_order');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('cost', 'valid', ['rule' => 'numeric']);
		$validator->add('manatype_id', 'valid', ['rule' => 'numeric']);
		$validator->add('mana_amount', 'valid', ['rule' => 'numeric']);
		$validator->add('sort_order', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('name', 'create');
		$validator->requirePresence('cost', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->addDelete([$this, 'ruleNoCharacters']);
		return $rules;
	}

	public function ruleNoCharacters($entity, $options)
	{
		$query = $this->CharactersSkills->find();
		$query->where(['skill_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}

		return true;
	}

}
