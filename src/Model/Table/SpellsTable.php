<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SpellsTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('spells');
		$this->displayField('name');
		$this->primaryKey('id');
		$this->belongsToMany('Characters');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');
		$validator->notEmpty('short');
		$validator->notEmpty('spiritual');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('spiritual', 'valid', ['rule' => 'boolean']);

		$validator->requirePresence('name', 'create');
		$validator->requirePresence('short', 'create');
		$validator->requirePresence('spiritual', 'create');

		return $validator;
	}

}
