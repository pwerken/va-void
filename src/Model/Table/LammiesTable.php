<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\ResultSet;

class LammiesTable extends Table
{
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
    }

    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
    }

    public function findLastInQueue(SelectQuery $query): SelectQuery
    {
        $query = $this->findQueued($query);
        $query->orderBy(['Lammies.id' => 'DESC']);
        $query->limit(1);

        return $query;
    }

    public function findQueued(SelectQuery $query): SelectQuery
    {
        $query = $this->findWithContain($query);
        $query->where(function (QueryExpression $exp) {
            return $exp->or(['status LIKE' => 'Queued'])
                        ->eq('status', 'Printing');
        });

        return $query;
    }

    public function findPrinting(SelectQuery $query): SelectQuery
    {
        $query = $this->findWithContain($query);
        $query->where(['status LIKE' => 'Printing']);

        return $query;
    }

    public function setStatuses(ResultSet $set, string $status): void
    {
        foreach ($set as $lammy) {
            $lammy->status = (is_null($lammy->lammy) ? 'Failed' : $status);
            $this->save($lammy);
        }
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}
