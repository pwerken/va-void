<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\ResultSet;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class LammiesTable
	extends AppTable
{

	public function findQueued(Query $query, array $options = [])
	{
		$query = $this->findWithContain($query, $options);

		$query->where(function(QueryExpression $exp) {
			return $exp->or(["status LIKE" => "Queued"])
						->eq("status", "Printing");
		});
		return $query;
	}

	public function findPrinting(Query $query, array $options = [])
	{
		$query = $this->findWithContain($query, $options);
		$query->where(["status LIKE" => "Printing"]);
		return $query;
	}

	public function findLastInQueue(Query $query, array $options = [])
	{
		$query = $this->findQueued($query, $options);
		$query->order(["Lammies.id" => "DESC"]);
		$query->limit(1);
		return $query;
	}

	public function beforeDelete(Event $event, EntityInterface $entity, $options)
	{
	}

	public function beforeSave(Event $event, EntityInterface $entity, $options)
	{
	}

	protected function orderBy()
	{
		return [ 'id' => 'ASC' ];
	}

	public function validationDefault(Validator $validator)
	{
		$validator->allowEmpty('id', 'create');
		$validator->notEmpty('entity');
		$validator->notEmpty('key1');
		$validator->allowEmpty('key2');
		$validator->notEmpty('job');
		$validator->notEmpty('printed');

		$validator->add('id',      'valid', ['rule' => 'numeric']);
		$validator->add('key1',    'valid', ['rule' => 'numeric']);
		$validator->add('key2',    'valid', ['rule' => 'numeric']);
		$validator->add('job',     'valid', ['rule' => 'numeric']);
		$validator->add('printed', 'valid', ['rule' => 'boolean']);

		$validator->requirePresence('entity', 'create');
		$validator->requirePresence('key1',   'create');
		$validator->requirePresence('job',    'create');

		return $validator;
	}

	public function setStatuses(ResultSet $set, $status)
	{
		foreach($set as $lammy) {
			$lammy->status = (is_null($lammy->lammy) ? 'Failed' : $status);
			$this->save($lammy);
		}
	}

}
