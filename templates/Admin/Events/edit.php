<?php
/**
 * @var \App\View\AdminView $this
 * @var \App\Model\Entity\Event $obj
 */

if ($obj->isNew()) {
    echo '<h3>Add Event</h3>';
} else {
    echo '<h3>Edit Event #' . h($obj->get('id')) . '</h3>';
}

echo $this->Form->create($obj, ['method' => 'post']);
echo $this->Form->control('name');
echo $this->Form->button('Save');
echo $this->Form->end();
