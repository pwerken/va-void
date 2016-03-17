<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SkillsTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('skills');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->belongsTo('Manatypes');
		$this->belongsToMany('Characters');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');
		$validator->notEmpty('cost');
		$validator->allowEmpty('manatype_id');
		$validator->allowEmpty('mana_amount');
		$validator->allowEmpty('sort_order');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('cost', 'valid', ['rule' => 'numeric']);
		$validator->add('manatype_id', 'valid', ['rule' => 'numeric']);
		$validator->add('mana_amount', 'valid', ['rule' => 'numeric']);
		$validator->add('sort_order', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('name', 'create');
		$validator->requirePresence('cost', 'create');

		return $validator;
	}

}
