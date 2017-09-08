<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class SpellsTable
	extends AppTable
{

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->hasMany('CharactersSpells')->setProperty('characters');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');
		$validator->notEmpty('short');
		$validator->notEmpty('spiritual');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('spiritual', 'valid', ['rule' => 'boolean']);

		$validator->requirePresence('name', 'create');
		$validator->requirePresence('short', 'create');
		$validator->requirePresence('spiritual', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->addDelete([$this, 'ruleNoCharacters']);
		return $rules;
	}

	public function ruleNoCharacters($entity, $options)
	{
		$query = $this->CharactersSpells->find();
		$query->where(['spell_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}

		return true;
	}

	protected function contain()
	{
		return [ 'CharactersSpells.Characters' ];
	}

	protected function orderBy()
	{
		return	[ 'name' => 'ASC' ];
	}
}
