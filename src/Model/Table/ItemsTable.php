<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Items Model
 */
class ItemsTable extends Table {

/**
 * Initialize method
 *
 * @param array $config The configuration for the Table.
 * @return void
 */
	public function initialize(array $config) {
		$this->table('items');
		$this->displayField('displayName');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('Characters');
		$this->belongsToMany('Attributes');
	}

/**
 * Default validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('id', 'create')
			->requirePresence('name', 'create')
			->notEmpty('name')
			->allowEmpty('description')
			->allowEmpty('player_text')
			->allowEmpty('cs_text')
			->add('character_id', 'valid', ['rule' => 'numeric'])
			->allowEmpty('character_id')
			->add('expiry', 'valid', ['rule' => 'date'])
			->allowEmpty('expiry');

		return $validator;
	}

	public function buildRules(RulesChecker $rules) {
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
		return null;
	}

}
