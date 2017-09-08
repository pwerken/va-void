<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class AttributesTable
	extends AppTable
{

	protected $_contain = [ 'AttributesItems.Items' ];

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->hasMany('AttributesItems')->setProperty('items');
	}

	public function orderBy()
	{
		return	[ 'name' => 'ASC', 'id' => 'ASC' ];
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->allowEmpty('name');
		$validator->allowEmpty('category');
		$validator->notEmpty('code');

		$validator->add('id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('code', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->addDelete([$this, 'ruleNoItems']);
		return $rules;
	}

	public function ruleNoItems($entity, $options)
	{
		$query = $this->AttributesItems->find();
		$query->where(['attributes_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('items', 'reference(s) present');
			return false;
		}

		return true;
	}

}
