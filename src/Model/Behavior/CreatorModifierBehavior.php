<?php
namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Entity;

use App\Utility\AuthState;

class CreatorModifierBehavior
#   extends \CreatorModifier\Model\Behavior\CreatorModifierBehavior
{
    public function getUserId()
    {
        return AuthState::getId();
    }

    /* Overrides that fix depricated warnings with CakePHP 3.6 */

    public function handleEvent(Event $event, Entity $entity) {
        $eventName = $event->getName(); // FIXED: ->name()
        $events = $this->_config['events'];

        $new = $entity->isNew() !== false;

        foreach ($events[$eventName] as $field => $when) {
            if (!in_array($when, ['always', 'new', 'existing'])) {
                throw new UnexpectedValueException(
                    sprintf('When should be one of "always", "new" or "existing". The passed value "%s" is invalid', $when)
                );
            }

            if ($when === 'always'
                || ($when === 'new' && $new)
                || ($when === 'existing' && !$new)
            ) {
                $this->updateField($entity, $field);
            }
        }

        return true;
    }

    public function createdOrModifed(Entity $entity, $eventName = 'Model.beforeSave') {
        $events = $this->_config['events'];
        if (empty($events[$eventName])) {
            return false;
        }

        $return = false;

        foreach ($events[$eventName] as $field => $when) {
            if (in_array($when, ['always', 'existing'])) {
                $return = true;
                $entity->setDirty($field, false); // FIXED: ->dirty($field, false)
                $this->updateField($entity, $field);
            }
        }

        return $return;
    }

    protected function updateField(Entity $entity, $field) {
        if ($entity->isDirty($field)) { // FIXED: ->dirty($field)
            return;
        }

        $entity->set($field, $this->getUserId());
    }

    protected function sessionUserId() {
        $userId = null;
        $request = $this->newRequest();

        if ($request->getSession()->started()) { // FIXED: ->session()
            $userId = $request->getSession()->read($this->_config['sessionUserIdKey']); // FIXED: ->session()
        } else {
            $this->log('The Session is not started. This typically means a User is not logged in. In this case there is no Session value for the currently active User and therefore we will set the `creator_id` and `modifier_id` to a null value. As a fallback, we are manually starting the session and reading the `$this->_config[sessionUserIdKey]` value, which is probably not correct.', 'debug');
            try {
                $request->getSession()->start(); // FIXED: ->session()
                $userId = $request->getSession()->read($this->_config['sessionUserIdKey']); // FIXED: ->session()
            } catch (RuntimeException $e) {
            }
        }

        return $userId;
    }
}
