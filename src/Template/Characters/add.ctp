<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('List Characters'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Players'), ['controller' => 'Players', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Player'), ['controller' => 'Players', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Factions'), ['controller' => 'Factions', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Faction'), ['controller' => 'Factions', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Believes'), ['controller' => 'Believes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Belief'), ['controller' => 'Believes', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Worlds'), ['controller' => 'Worlds', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New World'), ['controller' => 'Worlds', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Conditions'), ['controller' => 'Conditions', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Condition'), ['controller' => 'Conditions', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Powers'), ['controller' => 'Powers', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Power'), ['controller' => 'Powers', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Spells'), ['controller' => 'Spells', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Spell'), ['controller' => 'Spells', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="characters form large-10 medium-9 columns">
<?= $this->Form->create($character) ?>
	<fieldset>
		<legend><?= __('Add Character') ?></legend>
	<?php
		echo $this->Form->input('player_id', ['options' => $players]);
		echo $this->Form->input('chin');
		echo $this->Form->input('name');
		echo $this->Form->input('xp',[ 'step' => '0.5', 'val' => 15 ]);
		echo $this->Form->input('faction_id', ['options' => $factions]);
		echo $this->Form->input('belief_id', ['options' => $believes]);
		echo $this->Form->input('group_id', ['options' => $groups]);
		echo $this->Form->input('world_id', ['options' => $worlds]);
		echo $this->Form->input('status');
		echo $this->Form->input('comments');
		echo $this->Form->input('conditions._ids', ['options' => $conditions]);
		echo $this->Form->input('powers._ids', ['options' => $powers]);
		echo $this->Form->input('skills._ids', ['options' => $skills]);
		echo $this->Form->input('spells._ids', ['options' => $spells]);
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
