<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $attributesItem->attribute_id], ['confirm' => __('Are you sure you want to delete # {0}?', $attributesItem->attribute_id)]) ?></li>
		<li><?= $this->Html->link(__('List Attributes Items'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="attributesItems form large-10 medium-9 columns">
<?= $this->Form->create($attributesItem) ?>
	<fieldset>
		<legend><?= __('Edit Attributes Item') ?></legend>
	<?php
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
