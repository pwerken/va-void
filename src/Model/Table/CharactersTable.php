<?php
namespace App\Model\Table;

use App\Model\Entity\Character;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class CharactersTable
	extends AppTable
{

	protected $_contain =
		[ 'Believes', 'Factions', 'Groups', 'Players', 'Worlds', 'Items'
		, 'CharactersConditions' => [ 'Conditions' ]
		, 'CharactersPowers'     => [ 'Powers' ]
		, 'CharactersSkills'     => [ 'Skills' => [ 'Manatypes' ] ]
		, 'CharactersSpells'     => [ 'Spells' ]
		, 'Teachings' => [ 'Teacher', 'Student', 'Skills' => [ 'Manatypes' ]
						 , 'Started', 'Updated' ]
		, 'Students'  => [ 'Teacher', 'Student', 'Skills' => [ 'Manatypes' ]
						 , 'Started', 'Updated' ]
		];

	public function initialize(array $config)
	{
		$this->table('characters');
		$this->displayField('displayName');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('Players');
		$this->belongsTo('Teachings',
			[ 'className' => 'Teachings', 'foreignKey' => 'id'
			, 'bindingKey' => 'student_id', 'propertyName' => 'teacher' ]);
		$this->belongsTo('Factions', ['propertyName' => 'faction_object']);
		$this->belongsTo('Believes', ['propertyName' => 'belief_object']);
		$this->belongsTo('Groups', ['propertyName' => 'group_object']);
		$this->belongsTo('Worlds', ['propertyName' => 'world_object']);
		$this->hasMany('Students',
			[ 'className' => 'Teachings', 'foreignKey' => 'teacher_id'
			, 'bindingKey' => 'id', 'propertyName' => 'students']);
		$this->hasMany('Items');
		$this->hasMany('CharactersConditions', ['propertyName' => 'conditions']);
		$this->hasMany('CharactersPowers', ['propertyName' => 'powers']);
		$this->hasMany('CharactersSkills', ['propertyName' => 'skills']);
		$this->hasMany('CharactersSpells', ['propertyName' => 'spells']);
	}

	public function orderBy()
	{
		return [ 'player_id' => 'ASC', 'chin' => 'DESC' ];
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
		$validator->allowEmpty('comments');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('player_id', 'valid', ['rule' => 'numeric']);
		$validator->add('chin', 'valid', ['rule' => 'naturalNumber']);
		$validator->add('xp', 'valid', ['rule' => ['custom', '/^[0-9]*([\.][05])?$/']]);
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

}
