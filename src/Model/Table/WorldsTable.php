<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class WorldsTable
	extends AppTable
{

	protected $_contain = [ 'Characters' ];

	public function initialize(array $config)
	{
		$this->table('worlds');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->hasMany('Characters');
	}

	public function orderBy()
	{
		return	[ 'name' => 'ASC' ];
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');

		$validator->add('id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('name', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->isUnique(['name'], 'This name is already in use.'));
		$rules->addDelete([$this, 'ruleNoCharacters']);
		return $rules;
	}

	public function ruleNoCharacters($entity, $options)
	{
		$query = $this->Characters->find();
		$query->where(['world_id' => $entity->id]);
		if($query->count() > 0) {
			$entity->errors('characters', 'reference(s) present');
			return false;
		}
		return true;
	}

}
