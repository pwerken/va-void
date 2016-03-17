<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ManatypesTable
	extends Table
{

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

}
