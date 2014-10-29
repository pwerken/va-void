<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $charactersSpell->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersSpell->character_id)]) ?></li>
		<li><?= $this->Html->link(__('List Characters Spells'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Spells'), ['controller' => 'Spells', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Spell'), ['controller' => 'Spells', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersSpells form large-10 medium-9 columns">
<?= $this->Form->create($charactersSpell) ?>
	<fieldset>
		<legend><?= __('Edit Characters Spell') ?></legend>
	<?php
		echo $this->Form->input('level');
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
