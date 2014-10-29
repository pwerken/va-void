<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $spell->id], ['confirm' => __('Are you sure you want to delete # {0}?', $spell->id)]) ?></li>
		<li><?= $this->Html->link(__('List Spells'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="spells form large-10 medium-9 columns">
<?= $this->Form->create($spell) ?>
	<fieldset>
		<legend><?= __('Edit Spell') ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('short');
		echo $this->Form->input('spiritual');
		echo $this->Form->input('characters._ids', ['options' => $characters]);
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
