<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class PowersTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('powers');
		$this->displayField('displayName');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->belongsToMany('Characters');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');
		$validator->notEmpty('player_text');
		$validator->allowEmpty('cs_text');

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
		$query = TableRegistry::get('CharactersPowers')->find();
		$query->where(['power_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}

		return true;
	}

}
