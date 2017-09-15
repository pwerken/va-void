<?php
namespace App\Model\Table;

use App\Model\Entity\History;

use Cake\Datasource\EntityInterface;
use Cake\Database\Type\DateTimeType;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class HistoryTable
	extends AppTable
{

	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->belongsTo('Characters')->setForeignKey('key1')
			->conditions(['history.entity LIKE' => 'Characters%']);
		$this->belongsTo('Conditions')->setForeignKey('key2')
			->conditions(['history.entity' => 'CharactersCondition']);
		$this->belongsTo('Powers')->setForeignKey('key2')
			->conditions(['history.entity' => 'CharactersPower']);
		$this->belongsTo('Skills')->setForeignKey('key2')
			->conditions(['history.entity' => 'CharactersSkill']);
		$this->belongsTo('Spells')->setForeignKey('key2')
			->conditions(['history.entity' => 'CharactersSpell']);

		$this->belongsTo('Attributes')->setForeignKey('key1')
			->conditions(['history.entity' => 'AttributesItem']);
		$this->belongsTo('Items')->setForeignKey('key2')
			->conditions(['history.entity LIKE' => '%sItem']);
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('entity');
		$validator->notEmpty('key1');
		$validator->allowEmpty('key2');
		$validator->allowEmpty('data');
		$validator->allowEmpty('modified');
		$validator->allowEmpty('modifier_id');

		$validator->add('id',          'valid', ['rule' => 'numeric']);
		$validator->add('key1',        'valid', ['rule' => 'numeric']);
		$validator->add('key2',        'valid', ['rule' => 'numeric']);
		$validator->add('modifier_id', 'valid', ['rule' => 'numeric']);

		$validator->requirePresence('entity', 'create');
		$validator->requirePresence('key1',   'create');

		return $validator;
	}

	protected function orderBy()
	{
		return [ 'modified' => 'DESC', 'id' => 'DESC' ];
	}

	public function logDeletion(EntityInterface $entity)
	{
		$lastChange = $this->logChange($entity);

		$history = $this->newEntity();
		$history->set('entity', $lastChange->get('entity'));
		$history->set('key1', $lastChange->get('key1'));
		$history->set('key2', $lastChange->get('key2'));
		$history->set('data', NULL);

		$this->save($history);

		return $history;
	}

	public function logChange(EntityInterface $entity)
	{
		$history = History::fromEntity($entity);
		$this->save($history);
		return $history;
	}

	public function getAllLastModified()
	{
		$dateParser = new DateTimeType();
		$list = [];

		$result = TableRegistry::get('Characters')->find()
			->select(["key1" => "player_id", "key2" => "chin"
				, "modified" , "modifier_id"])
			->where(["modified IS NOT" => "NULL"])
			->order(['modified' => 'DESC'])
			->hydrate(false)->all();
		foreach($result as $row) {
			$row['entity'] = 'Character';
			$row['modified'] = $dateParser->marshal($row['modified'])->jsonSerialize();
			$list[] = $row;
		}

		$entities = ['Player', 'Condition', 'Power', 'Item'];
		foreach($entities as $entity) {
			$result = TableRegistry::get($entity.'s')->find()
				->select(["key1" => "id", "modified", "modifier_id"])
				->where(["modified IS NOT" => "NULL"])
				->order(['modified' => 'DESC', 'key1' => 'ASC'])
				->hydrate(false)->all();
			foreach($result as $row) {
				$row['entity'] = $entity;
				$row['key2'] = NULL;
				$row['modified'] = $dateParser->marshal($row['modified'])->jsonSerialize();
				$list[] = $row;
			}
		}

		usort($list, array($this, "compare"));
		return $list;
	}

	public function getEntityHistory($entity, $key1, $key2)
	{
		switch(strtolower($entity)) {
		case 'player':		return $this->getPlayerHistory($key1);
		case 'character':	return $this->getCharacterHistory($key1, $key2);
		case 'condition':	return $this->getConditionHistory($key1);
		case 'power':		return $this->getPowerHistory($key1);
		case 'item':		return $this->getItemHistory($key1);
		default:			return [];
		}
	}

	private function getPlayerHistory($plin)
	{
		$entity = TableRegistry::get('players')->find()
			->where(['id' => $plin])
			->first();

		if(is_null($entity))
			return [];

		$list = $this->find()
			->where(['entity' => 'Player', 'key1' => $plin])
			->all()->toList();

		$list[] = History::fromEntity($entity);

		usort($list, array("App\Model\Entity\History", "compare"));
		return $list;
	}

	private function getCharacterHistory($plin, $chin)
	{
		$entity = TableRegistry::get('characters')->find('withContain')
			->where(['characters.player_id' => $plin])
			->where(['characters.chin' => $chin])
			->first();

		if(is_null($entity))
			return [];

		$id = $entity->get('id');
		$list = $this->find()
			->where(['entity LIKE' => 'Character%', 'key1' => $id])
			->contain(['Conditions', 'Powers', 'Skills', 'Spells'])
			->all()->toList();

		$list[] = History::fromEntity($entity);
		foreach($entity->get('conditions') as $condition) {
			$list[] = History::fromEntity($condition);
		}
		foreach($entity->get('powers') as $power) {
			$list[] = History::fromEntity($power);
		}
		foreach($entity->get('skills') as $skill) {
			$list[] = History::fromEntity($skill);
		}
		foreach($entity->get('spells') as $spell) {
			$list[] = History::fromEntity($spell);
		}

		usort($list, array("App\Model\Entity\History", "compare"));
		return $list;
	}

	private function getConditionHistory($coin)
	{
		$entity = TableRegistry::get('conditions')->find('withContain')
			->where(['conditions.id' => $coin])
			->first();

		if(is_null($entity))
			return [];

		$list = $this->find()
			->where(['entity' => 'Condition', 'key1' => $coin])
			->orWhere(['AND' => ['entity' => 'CharactersCondition', 'key2' => $coin]])
			->contain(['Characters'])
			->all()->toList();

		$list[] = History::fromEntity($entity);
		foreach($entity->get('characters') as $character) {
			$list[] = History::fromEntity($character);
		}

		usort($list, array("App\Model\Entity\History", "compare"));
		return $list;
	}

	private function getPowerHistory($poin)
	{
		$entity = TableRegistry::get('powers')->find('withContain')
			->where(['powers.id' => $poin])
			->first();

		if(is_null($entity))
			return [];

		$list = $this->find()
			->where(['entity' => 'Power', 'key1' => $poin])
			->orWhere(['AND' => ['entity' => 'CharactersPower', 'key2' => $poin]])
			->contain(['Characters'])
			->all()->toList();

		$list[] = History::fromEntity($entity);
		foreach($entity->get('characters') as $character) {
			$list[] = History::fromEntity($character);
		}

		usort($list, array("App\Model\Entity\History", "compare"));
		return $list;
	}

	private function getItemHistory($itin)
	{
		$entity = TableRegistry::get('items')->find('withContain')
			->where(['items.id' => $itin])
			->first();

		if(is_null($entity))
			return [];

		$list = $this->find()
			->where(['entity' => 'Item', 'key1' => $itin])
			->orWhere(['AND' => ['entity' => 'AttributesItem', 'key2' => $itin]])
			->contain(['Attributes'])
			->all()->toList();

		$list[] = History::fromEntity($entity);
		foreach($entity->get('attributes') as $attribute) {
			$list[] = History::fromEntity($attribute);
		}

		usort($list, array("App\Model\Entity\History", "compare"));
		return $list;
	}

	public static function compare($a, $b)
	{
		if(is_null($a) && is_null($b))
			return 0;
		else if(is_null($a))
			return 1;
		else if(is_null($b))
			return -1;

		$cmp = strcmp($b['modified'], $a['modified']);
		if($cmp != 0)
			return $cmp;

		$cmp = strcmp($a['entity'], $b['entity']);
		if($cmp != 0)
			return $cmp;

		$cmp = $a['key1'] - $b['key1'];
		if($cmp != 0)
			return $cmp;

		return $a['key2'] - $b['key2'];
	}
}
