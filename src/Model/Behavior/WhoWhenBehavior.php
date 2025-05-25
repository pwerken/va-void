<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use App\Model\Entity\History;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\ORM\Behavior;
use Cake\Routing\Router;

/**
 * Class WhoWhenBehavior
 */
class WhoWhenBehavior extends Behavior
{
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity instanceof History && !is_null($entity->data)) {
            return;
        }

        if ($entity->isNew()) {
            if ($this->table()->hasField('created')) {
                $entity->set('created', $this->when());
            }
            if ($this->table()->hasField('creator_id')) {
                $entity->set('creator_id', $this->who());
            }
        }

        $this->touch($entity);
    }

    public function touch(EntityInterface $entity): bool
    {
        $modified = false;

        if ($this->table()->hasField('modified')) {
            $entity->set('modified', $this->when());
            $modified = true;
        }
        if ($this->table()->hasField('modifier_id')) {
            $entity->set('modifier_id', $this->who());
            $modified = true;
        }

        return $modified;
    }

    protected function when(): DateTime
    {
        return new DateTime();
    }

    protected function who(): int
    {
        return Router::getRequest()->getAttribute('identity')->id;
    }
}
