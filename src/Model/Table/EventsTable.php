<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;

/**
 * @extends \App\Model\Table\Table<\App\Model\Entity\Event>
 */
class EventsTable extends Table
{
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->add($rules->isUnique(['name']));

        return $rules;
    }

    protected function orderBy(): array
    {
        return ['id' => 'DESC'];
    }
}
