<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class PowersTable
	extends AppTable
{

	public function initialize(array $config): void
	{
		parent::initialize($config);

		$this->hasMany('CharactersPowers')->setProperty('characters');
	}

	public function validationDefault(Validator $validator): Validator
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

	public function buildRules(RulesChecker $rules): RulesChecker
	{
		$rules->addDelete([$this, 'ruleNoCharacters']);
		return $rules;
	}

	public function ruleNoCharacters($entity, $options)
	{
		$query = $this->CharactersPowers->find();
		$query->where(['power_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}

		return true;
	}

	protected function contain(): array
	{
		return [ 'CharactersPowers.Characters' ];
	}

	protected function orderBy(): array
	{
		return	[ 'id' => 'ASC' ];
	}
}
