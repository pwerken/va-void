<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\ORM\Behavior;
use Cake\Routing\Router;

use ArrayObject;

use App\Model\Entity\History;

/**
 * Class WhoWhenBehavior
 */
class WhoWhenBehavior
    extends Behavior
{
    protected $_when;

    protected $_who;

    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if($entity instanceof History and !is_null($entity->data)) {
            return;
        }

        if($entity->isNew() !== false)
        {
            if($this->table()->hasField('created')) {
                $entity->set('created', $this->when());
            }
            if($this->table()->hasField('creator_id')) {
                $entity->set('creator_id', $this->who());
            }
        }

        $this->touch($entity);
    }

    public function touch(EntityInterface $entity): bool
    {
        $return = false;

        if($this->table()->hasField('modified')) {
            $entity->set('modified', $this->when());
            $return = true;
        }
        if($this->table()->hasField('modifier_id')) {
            $entity->set('modifier_id', $this->who());
            $return = true;
        }

        return $return;
    }

    protected function when(): DateTime
    {
        if($this->_when === null) {
            $this->_when = new DateTime();
        }

        return $this->_when;
    }

    protected function who(): int
    {
        if($this->_who === null) {
            $this->_who = Router::getRequest()->getAttribute('identity')->id;
        }

        return $this->_who;
    }
}
