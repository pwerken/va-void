<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $charactersCondition->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersCondition->character_id)]) ?></li>
		<li><?= $this->Html->link(__('List Characters Conditions'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Conditions'), ['controller' => 'Conditions', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Condition'), ['controller' => 'Conditions', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersConditions form large-10 medium-9 columns">
<?= $this->Form->create($charactersCondition) ?>
	<fieldset>
		<legend><?= __('Edit Characters Condition') ?></legend>
	<?php
		echo $this->Form->input('expiry');
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
