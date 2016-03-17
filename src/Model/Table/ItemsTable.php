<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ItemsTable
	extends Table
{

	public function initialize(array $config)
	{
		$this->table('items');
		$this->displayField('displayName');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('Characters');
		$this->belongsToMany('Attributes');
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');
		$validator->allowEmpty('description');
		$validator->allowEmpty('player_text');
		$validator->allowEmpty('cs_text');
		$validator->allowEmpty('character_id');
		$validator->allowEmpty('expiry');

		$validator->add('id', 'valid', ['rule' => 'numeric']);
		$validator->add('character_id', 'valid', ['rule' => 'numeric']);
		$validator->add('expiry', 'valid', ['rule' => 'date']);

		$validator->requirePresence('name', 'create');

		return $validator;
	}

	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->existsIn('character_id', 'characters'));
		return $rules;
	}

	protected function _newID($primary)
	{
		$holes = [ 1980, 2201, 2300, 8001, 8888, 9000, 9999, -1 ];
		foreach($holes as $max) {
			$query = $this->find()->hydrate(false)->select(['id' => 'MAX(id)']);
			if($max > 0)
				$query->where(['id <' => $max]);

			$newID = $query->toArray()['id'] + 1;
			if($newID < $max || $max < 0)
				return $newID;
		}
		return NULL;
	}

}
