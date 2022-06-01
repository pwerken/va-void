<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class CharactersSkillsTable
	extends AppTable
{

	public function initialize(array $config): void
	{
		parent::initialize($config);

		$this->setPrimaryKey(['character_id', 'skill_id']);

		$this->belongsTo('Characters');
		$this->belongsTo('Skills');
	}

	public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
	{
		$this->touchEntity('Characters', $entity->character_id);
	}

	public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
	{
		$this->touchEntity('Characters', $entity->character_id);
	}

	public function validationDefault(Validator $validator): Validator
	{
		$validator->notEmpty('character_id');
		$validator->notEmpty('skill_id');

		$validator->add('character_id', 'valid', ['rule' => 'numeric']);
		$validator->add('skill_id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('character_id', 'create');
		$validator->requirePresence('skill_id', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules): RulesChecker
	{
		$rules->add($rules->existsIn('character_id', 'Characters'));
		$rules->add($rules->existsIn('skill_id', 'Skills'));

		$rules->addCreate([$this, 'disallowDeprecated']);
#		$rules->addCreate([$this, 'hasXPAvailable']);

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
		foreach($character->skills as $skill) {
			$spend += $skill->skill->cost;
		}

		if($spend + $cost > $total) {
			$entity->errors('character_id', 'insufficient XP');
			return false;
		}

		return true;
	}

	protected function contain(): array
	{
		return [ 'Characters', 'Skills.Manatypes' ];
	}
}
