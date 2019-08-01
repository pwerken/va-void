<?php
namespace App\Model\Table;

use App\Model\Entity\Character;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class CharactersTable
	extends AppTable
{

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->belongsTo('Players');
		$this->belongsTo('Factions')->setProperty('faction_object');
		$this->belongsTo('Believes')->setProperty('belief_object');
		$this->belongsTo('Groups')->setProperty('group_object');
		$this->belongsTo('Worlds')->setProperty('world_object');

		$this->hasMany('Items');
		$this->hasMany('CharactersConditions')->setProperty('conditions');
		$this->hasMany('CharactersPowers')->setProperty('powers');
		$this->hasMany('CharactersSkills')->setProperty('skills');
		$this->hasMany('CharactersSpells')->setProperty('spells');

		$this->hasMany('MyStudents', ['className' => 'Teachings'])
				->setForeignKey('teacher_id')->setProperty('students');
		$this->hasOne('MyTeacher', ['className' => 'Teachings'])
				->setForeignKey('student_id')->setProperty('teacher');
	}

	public function afterSave(Event $event, EntityInterface $entity, $options)
	{
		if($entity->isDirty('status') && $entity->status == 'active') {
			$chars = $this->findByPlayerId($entity->player_id);
			foreach($chars as $char) {
				if($char->id == $entity->id || $char->status != 'active') {
					continue;
				}
				$char->status = 'inactive';
				$this->save($char);
			}
		}
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('player_id');
		$validator->notEmpty('chin');
		$validator->notEmpty('name');
		$validator->notEmpty('xp');
		$validator->notEmpty('faction_id');
		$validator->notEmpty('belief_id');
		$validator->notEmpty('group_id');
		$validator->notEmpty('world_id');
		$validator->allowEmpty('soulpath');
		$validator->notEmpty('status');
		$validator->allowEmpty('referee_notes');
		$validator->allowEmpty('notes');

		// regex for xp validation
		$xp_regex = '/^[0-9]*(?:[.,](?:[05][0]?|[27]5))$/';

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('player_id', 'valid', ['rule' => 'numeric']);
		$validator->add('chin', 'valid', ['rule' => 'naturalNumber']);
		$validator->add('xp', 'valid', ['rule' => ['custom', $xp_regex]]);
		$validator->add('faction_id', 'valid', ['rule' => 'numeric']);
		$validator->add('belief_id', 'valid', ['rule' => 'numeric']);
		$validator->add('group_id', 'valid', ['rule' => 'numeric']);
		$validator->add('world_id', 'valid', ['rule' => 'numeric']);
		$validator->add('soulpath', 'valid', ['rule' => ['inList', Character::soulpathValues()]]);
		$validator->add('status', 'valid', ['rule' => ['inList', Character::statusValues()]]);

		$validator->requirePresence('player_id', 'create');
		$validator->requirePresence('chin', 'create');
		$validator->requirePresence('name', 'create');

		return $validator;
	}

	public function plinChin($plin, $chin)
	{
		return $this->findByPlayerIdAndChin($plin, $chin)->firstOrFail();
	}

	public function buildRules(RulesChecker $rules)
	{

		$rules->add($rules->isUnique(['player_id', 'chin'],
			'This plin & chin combination is already in use.'));

		$rules->add($rules->existsIn('faction_id', 'factions'));
		$rules->add($rules->existsIn('group_id', 'groups'));
		$rules->add($rules->existsIn('belief_id', 'believes'));
		$rules->add($rules->existsIn('world_id', 'worlds'));

		$rules->addDelete([$this, 'ruleNoConditions']);
		$rules->addDelete([$this, 'ruleNoItems']);
		$rules->addDelete([$this, 'ruleNoPowers']);
		$rules->addDelete([$this, 'ruleNoSkills']);
		$rules->addDelete([$this, 'ruleNoSpells']);

		return $rules;
	}

	public function ruleNoConditions($entity, $options)
	{
		$query = $this->CharactersConditions->find();
		$query->where(['character_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('conditions', 'reference(s) present');
			return false;
		}

		return true;
	}
	public function ruleNoItems($entity, $options)
	{
		$query = $this->Items->find();
		$query->where(['character_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('items', 'reference(s) present');
			return false;
		}

		return true;
	}
	public function ruleNoPowers($entity, $options)
	{
		$query = $this->CharactersPowers->find();
		$query->where(['character_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('powers', 'reference(s) present');
			return false;
		}

		return true;
	}
	public function ruleNoSkills($entity, $options)
	{
		$query = $this->CharactersSkills->find();
		$query->where(['character_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('skills', 'reference(s) present');
			return false;
		}

		return true;
	}
	public function ruleNoSpells($entity, $options)
	{
		$query = $this->CharactersSpells->find();
		$query->where(['character_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('spells', 'reference(s) present');
			return false;
		}

		return true;
	}

	protected function contain()
	{
		return
			[ 'Believes', 'Factions', 'Groups', 'Players', 'Worlds', 'Items'
			, 'CharactersConditions.Conditions'
			, 'CharactersPowers.Powers'
			, 'CharactersSkills.Skills.Manatypes'
			, 'CharactersSpells.Spells'
			, 'MyTeacher'  =>	[ 'Teacher', 'Student', 'Skills.Manatypes'
								, 'Started', 'Updated' ]
			, 'MyStudents' =>	[ 'Teacher', 'Student', 'Skills.Manatypes'
								, 'Started', 'Updated' ]
			];
	}

	protected function orderBy()
	{
		return [ 'player_id' => 'ASC', 'chin' => 'DESC' ];
	}
}
