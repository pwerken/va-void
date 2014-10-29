<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $charactersSkill->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersSkill->character_id)]) ?></li>
		<li><?= $this->Html->link(__('List Characters Skills'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersSkills form large-10 medium-9 columns">
<?= $this->Form->create($charactersSkill) ?>
	<fieldset>
		<legend><?= __('Edit Characters Skill') ?></legend>
	<?php
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
