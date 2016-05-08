<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ItemsTable
	extends AppTable
{

	protected $_contain = [ 'Characters', 'AttributesItems' => [ 'Items' ] ];

	public function initialize(array $config)
	{
		$this->table('items');
		$this->displayField('displayName');
		$this->primaryKey('id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('Characters');
		$this->hasMany('AttributesItems');
	}

	public function findAll(Query $query, array $options)
	{
		return $query->contain(['Characters']);
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('name');
		$validator->allowEmpty('description');
		$validator->allowEmpty('important');
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
		$rules->addDelete([$this, 'ruleNoCharacter']);
		$rules->addDelete([$this, 'ruleNoAttributes']);
		return $rules;
	}

	public function ruleNoCharacter($entity, $options)
	{
		if(!empty($entity->get('character_id'))) {
			$entity->errors('character_id', 'reference present');
			return false;
		}

		return true;
	}

	public function ruleNoAttributes($entity, $options)
	{
		$query = $this->AttributesItems->find();
		$query->where(['item_id' => $entity->id]);

		if($query->count() > 0) {
			$entity->errors('attributes', 'reference(s) present');
			return false;
		}

		return true;
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
