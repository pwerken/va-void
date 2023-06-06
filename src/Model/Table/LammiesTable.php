<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\ResultSet;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class LammiesTable
    extends AppTable
{
    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
    }

    public function findLastInQueue(Query $query, array $options = [])
    {
        $query = $this->findQueued($query, $options);
        $query->order(["Lammies.id" => "DESC"]);
        $query->limit(1);
        return $query;
    }

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

    public function setStatuses(ResultSet $set, $status)
    {
        foreach($set as $lammy) {
            $lammy->status = (is_null($lammy->lammy) ? 'Failed' : $status);
            $this->save($lammy);
        }
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}
