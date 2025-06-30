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
        if ($entity instanceof History && !$entity->isEmpty('data')) {
            return;
        }

        $when = new DateTime();
        $who = Router::getRequest()?->getAttribute('identity')->id;

        if ($entity->isNew()) {
            if ($this->table()->hasField('created')) {
                $entity->set('created', $when);
            }
            if ($this->table()->hasField('creator_id')) {
                $entity->set('creator_id', $who);
            }
        }

        if ($this->table()->hasField('modified')) {
            $entity->set('modified', $when);
        }
        if ($this->table()->hasField('modifier_id')) {
            $entity->set('modifier_id', $who);
        }
    }
}
