<?php
namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class CharactersSkillsTable
	extends AppTable
{

	protected $_contain = [ 'Characters', 'Skills.Manatypes' ];

	public function initialize(array $config)
	{
		$this->table('characters_skills');
		$this->primaryKey(['character_id', 'skill_id']);
		$this->belongsTo('Characters');
		$this->belongsTo('Skills');
	}

	public function afterDelete(Event $event, EntityInterface $entity, $options)
	{
		$this->touchEntity('Characters', $entity->character_id);
	}

	public function afterSave(Event $event, EntityInterface $entity, $options)
	{
		$this->touchEntity('Characters', $entity->character_id);
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

		$rules->addCreate([$this, 'disallowDeprecated']);
		$rules->addCreate([$this, 'hasXPAvailable']);

		return $rules;
	}

	public function disallowDeprecated($entity, $options)
	{
		$skill = $this->Skills->findWithContainById($entity->skill_id)->first();
		if($skill->deprecated) {
			$entity->errors('skill_id', 'deprecated skill');
			return false;
		}

		return true;
	}

	public function hasXPAvailable($entity, $options)
	{
		$character = $this->Characters->findWithContainById($entity->character_id)->first();
		$skill = $this->Skills->findWithContainById($entity->skill_id)->first();

		$total = $character->xp;
		$cost = $skill->cost;

		$spend = 0;
		foreach($character->characters_skills as $skill) {
			$spend += $skill->skill->cost;
		}

		if($spend + $cost > $total) {
			$entity->errors('character_id', 'insufficient XP');
			return false;
		}

		return true;
	}

}
