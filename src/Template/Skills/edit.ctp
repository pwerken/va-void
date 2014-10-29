<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $skill->id], ['confirm' => __('Are you sure you want to delete # {0}?', $skill->id)]) ?></li>
		<li><?= $this->Html->link(__('List Skills'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Manatypes'), ['controller' => 'Manatypes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Manatype'), ['controller' => 'Manatypes', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="skills form large-10 medium-9 columns">
<?= $this->Form->create($skill) ?>
	<fieldset>
		<legend><?= __('Edit Skill') ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('cost');
		echo $this->Form->input('manatype_id', ['options' => $manatypes]);
		echo $this->Form->input('mana_amount');
		echo $this->Form->input('sort_order');
		echo $this->Form->input('characters._ids', ['options' => $characters]);
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
