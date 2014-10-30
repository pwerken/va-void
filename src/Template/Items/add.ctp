<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('List Items'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="items form large-10 medium-9 columns">
<?= $this->Form->create($item) ?>
	<fieldset>
		<legend><?= __('Add Item') ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('player_text');
		echo $this->Form->input('cs_text');
		echo $this->Form->input('character_id', ['options' => $characters]);
		echo $this->Form->input('expiry');
		echo $this->Form->input('attributes._ids', ['options' => $attributes]);
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
