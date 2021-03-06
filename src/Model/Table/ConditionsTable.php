<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class ConditionsTable
	extends AppTable
{

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->hasMany('CharactersConditions')->setProperty('characters');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');
		$validator->notEmpty('player_text');
		$validator->allowEmpty('referee_notes');
		$validator->allowEmpty('notes');

		$validator->add('id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('name', 'create');
		$validator->requirePresence('player_text', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->addDelete([$this, 'ruleNoCharacters']);
		return $rules;
	}

	public function ruleNoCharacters($entity, $options)
	{
		$query = $this->CharactersConditions->find();
		$query->where(['condition_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}

		return true;
	}

	protected function contain()
	{
		return [ 'CharactersConditions.Characters' ];
	}

	protected function orderBy()
	{
		return	[ 'id' => 'ASC' ];
	}
}
