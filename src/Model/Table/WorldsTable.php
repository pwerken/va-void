<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class WorldsTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('worlds');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->hasMany('Characters');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');

		$validator->add('id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('name', 'create');

		return $validator;
	}

}
