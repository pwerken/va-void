<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class SpellsTable
	extends AppTable
{

	protected $_contain = [ 'CharactersSpells' => [ 'Characters' ] ];

	public function initialize(array $config)
	{
		$this->table('spells');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->hasMany('CharactersSpells')->setProperty('characters');
	}

	public function orderBy()
	{
		return	[ 'name' => 'ASC' ];
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

}
