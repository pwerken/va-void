<?php

use App\Model\Entity\Player;

?>
<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('List Players'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="players form large-10 medium-9 columns">
<?= $this->Form->create($player) ?>
	<fieldset>
		<legend><?= __('Add Player') ?></legend>
	<?php
		echo $this->Form->input('id',
				[ 'type' => 'number'
				, 'label' => __('Plin')
				]);
		echo $this->Form->input('account_type',
				[ 'type' => 'select'
				, 'options' => Player::validAccountTypes()
				]);
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		?><hr><?php
		echo $this->Form->input('first_name');
		echo $this->Form->input('insertion');
		echo $this->Form->input('last_name');
		?><hr><?php
		echo $this->Form->input('gender',
				[ 'type' => 'select'
				, 'options' => Player::validGenders()
				, 'empty' => true
				]);
		echo $this->Form->input('date_of_birth',
				[ 'minYear' => date('Y') - 90
				, 'maxYear' => date('Y')
				, 'empty' => true
				, 'val' => ''
				]);
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
