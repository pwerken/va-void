<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AttributesTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('attributes');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->belongsToMany('Items');
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

}
