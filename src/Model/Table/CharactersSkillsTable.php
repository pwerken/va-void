<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class CharactersSkillsTable
	extends AppTable
{

	protected $_contain = [ 'Characters', 'Skills' => [ 'Manatypes' ] ];

	public function initialize(array $config)
	{
		$this->table('characters_skills');
		$this->displayField('character_id');
		$this->primaryKey(['character_id', 'skill_id']);
		$this->belongsTo('Characters');
		$this->belongsTo('Skills');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->notEmpty('character_id');
		$validator->notEmpty('skill_id');

		$validator->add('character_id', 'valid', ['rule' => 'numeric']);
		$validator->add('skill_id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('character_id', 'create');
		$validator->requirePresence('skill_id', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->existsIn('character_id', 'characters'));
		$rules->add($rules->existsIn('skill_id', 'skills'));

		$rules->addCreate([$this, 'addRelations']);
		$rules->addCreate([$this, 'ruleXPAvailable']);

		return $rules;
	}

	public function addRelations($entity, $options)
	{
		$entity->Character = $this->Characters->findWithContainById($entity->character_id)->first();
		$entity->Skill = $this->Skills->findWithContainById($entity->skill_id)->first();
	}

	public function ruleXPAvailable($entity, $options)
	{
		if(!$entity->Character || !$entity->Skill)
			return false;

		$total = $entity->Character->xp;
		$cost = $entity->Skill->cost;

		$spend = 0;
		foreach($entity->Character->characters_skills as $skill) {
			$spend += $skill->skill->cost;
		}

		if($spend + $cost > $total) {
			$entity->errors('character_id', 'insufficient XP');
			return false;
		}

		return true;
	}
}
