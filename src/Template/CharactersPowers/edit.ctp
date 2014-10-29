<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $charactersPower->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersPower->character_id)]) ?></li>
		<li><?= $this->Html->link(__('List Characters Powers'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Powers'), ['controller' => 'Powers', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Power'), ['controller' => 'Powers', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersPowers form large-10 medium-9 columns">
<?= $this->Form->create($charactersPower) ?>
	<fieldset>
		<legend><?= __('Edit Characters Power') ?></legend>
	<?php
		echo $this->Form->input('expiry');
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
