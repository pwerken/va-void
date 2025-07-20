<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;

class SocialProfilesTable extends Table
{
    public function beforeDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // don't log changes to History table
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        // don't log changes to History table
    }

    protected function orderBy(): array
    {
        return ['user_id' => 'ASC', 'modified' => 'DESC'];
    }
}
