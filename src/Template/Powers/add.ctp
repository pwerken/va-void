<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('List Powers'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="powers form large-10 medium-9 columns">
<?= $this->Form->create($power) ?>
	<fieldset>
		<legend><?= __('Add Power') ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('player_text');
		echo $this->Form->input('cs_text');
		echo $this->Form->input('characters._ids', ['options' => $characters]);
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
