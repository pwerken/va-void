<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Enum\LammyStatus;
use ArrayObject;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;

class LammiesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setColumnEnumType('status', LammyStatus::class);
    }

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
            return $exp->or(['status LIKE' => LammyStatus::Queued])
                        ->eq('status', LammyStatus::Printing);
        });

        return $query;
    }

    public function findPrinting(SelectQuery $query): SelectQuery
    {
        $query = $this->findWithContain($query);
        $query->where(['status LIKE' => LammyStatus::Printing]);

        return $query;
    }

    public function setStatuses(ResultSetInterface $set, LammyStatus $status): void
    {
        foreach ($set as $lammy) {
            $lammy->status = (is_null($lammy->lammy) ? LammyStatus::Failed : $status);
            $this->save($lammy);
        }
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}
