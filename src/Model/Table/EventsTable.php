<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

class EventsTable
    extends AppTable
{
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name']));

        return $rules;
    }

    protected function orderBy(): array
    {
        return ['id' => 'ASC'];
    }
}
