<?php
/**
 * @var \App\View\AdminView $this
 * @var \App\Model\Entity\Event $obj
 */

if ($obj->isNew()) {
    echo '<h3>Add Manatype</h3>';
} else {
    echo '<h3>Edit Manatype #' . $obj->id . '</h3>';
}

echo $this->Form->create($obj, ['method' => 'post']);
echo $this->Form->control('name');
echo $this->Form->control('deprecated');
echo $this->Form->button('Save');
echo $this->Form->end();
