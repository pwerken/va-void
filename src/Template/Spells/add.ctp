<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('List Spells'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="spells form large-10 medium-9 columns">
<?= $this->Form->create($spell) ?>
	<fieldset>
		<legend><?= __('Add Spell') ?></legend>
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
