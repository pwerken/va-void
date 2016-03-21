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

		$rules->addCreate([$this, 'ruleXPAvailable']);

		return $rules;
	}

	public function ruleXPAvailable($entity, $options)
	{
		$total = $this->Characters->get($entity->character_id)->xp;
		$cost = $this->Skills->get($entity->skill_id)->cost;

		$query = $this->find()->contain(['Skills'])->hydrate(false);
		$query->select(['spend' => 'SUM(Skills.cost)']);
		$query->where(['character_id' => $entity->character_id]);
		$spend = (int)$query->first()['spend'];

		if($spend + $cost > $total) {
			$entity->errors('character_id', 'insufficient XP');
			return false;
		}

		return true;
	}
}
