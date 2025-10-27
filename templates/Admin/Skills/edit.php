<?php
declare(strict_types=1);

/**
 * @var \App\View\AdminView $this
 * @var \App\Model\Entity\Event $obj
 */

if ($obj->isNew()) {
    echo '<h3>Add Skill</h3>';
} else {
    echo '<h3>Edit Skill #' . h($obj->get('id')) . '</h3>';
}

echo $this->Form->create($obj, ['method' => 'post']);
echo $this->Form->control('name');
echo $this->Form->control('cost');
echo $this->Form->control('sort_order');
echo "<br/>\n";
echo $this->Form->control('times_max');
echo $this->Form->control('base_max');
echo "<br/>\n";
echo $this->Form->control('mana_amount');
echo $this->Form->control('manatype_id');
echo "<br/>\n";
echo $this->Form->control('loresheet');
echo "<br/>\n";
echo $this->Form->control('blanks');
echo "<br/>\n";
echo $this->Form->control('deprecated');
echo "<br/>\n";
echo $this->Form->button('Save');
echo $this->Form->end();
