<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class ManatypesTable
	extends AppTable
{

	protected $_contain = [ 'Skills' ];

	public function initialize(array $config)
	{
		$this->table('manatypes');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->hasMany('Skills');
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
		$rules->addDelete([$this, 'ruleNoSkills']);
		return $rules;
	}

	public function ruleNoSkills($entity, $options)
	{
		$query = $this->Skills->find();
		$query->where(['manatype_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('skills', 'reference(s) present');
			return false;
		}

		return true;
	}

}
