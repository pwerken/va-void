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

    public function findAdminListing(SelectQuery $query): SelectQuery
    {
        $related = [
            'Character',
            'CharactersGlyphImbue',
            'CharactersRuneImbue',
            'CharactersSkill',
            'CharactersPower',
            'CharactersCondition',
            'Teaching',
        ];

        return $query
            ->select($this)
            ->select(['character_str' => $query->func()->concat([
                'characters.plin' => 'identifier',
                '/',
                'characters.chin' => 'identifier',
            ])])
            ->leftJoin('characters', [
                'Lammies.entity IN' => $related,
                'Lammies.key1 = characters.id',
            ])
            ->orderBy(['Lammies.id' => 'DESC'], true);
    }

    public function findLastInQueue(SelectQuery $query): SelectQuery
    {
        return $this->findQueued($query)
            ->orderBy(['Lammies.id' => 'DESC'])
            ->limit(1);
    }

    public function findQueued(SelectQuery $query): SelectQuery
    {
        return $this->findWithContain($query)
            ->where(function (QueryExpression $exp) {
                return $exp->or(['status LIKE' => LammyStatus::Queued])
                            ->eq('status', LammyStatus::Printing);
            });
    }

    public function findPrinting(SelectQuery $query): SelectQuery
    {
        return $this->findWithContain($query)
            ->where(['status LIKE' => LammyStatus::Printing]);
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
