<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $manatype->id], ['confirm' => __('Are you sure you want to delete # {0}?', $manatype->id)]) ?></li>
		<li><?= $this->Html->link(__('List Manatypes'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="manatypes form large-10 medium-9 columns">
<?= $this->Form->create($manatype) ?>
	<fieldset>
		<legend><?= __('Edit Manatype') ?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
