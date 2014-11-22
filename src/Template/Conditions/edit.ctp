<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $condition->id], ['confirm' => __('Are you sure you want to delete # {0}?', $condition->id)]) ?></li>
		<li><?= $this->Html->link(__('List Conditions'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="conditions form large-10 medium-9 columns">
<?= $this->Form->create($condition) ?>
	<fieldset>
		<legend><?= __('Edit Condition') ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('player_text', [ 'rows' => 5 ]);
		echo $this->Form->input('cs_text', [ 'rows' => 5 ]);
	?>
	</fieldset>
	<fieldset>
		<legend><?= __('Related Characters') ?></legend>
	<?php
		$i = 0;
		foreach($condition->characters as $character) {
			if($i > 0) echo "<hr>\n";
			echo $this->Form->input("characters.$i.id",
				[ 'type' => 'select'
				, 'label' => __('Character')
				, 'options' => $characters
				, 'empty' => true
				, 'required' => false
				]);

			$expiry = $character->_joinData['expiry'];
			echo $this->Form->input("characters.$i._joinData.expiry",
				[ 'type' => 'date'
				, 'label' => __('Expiry')
				, 'empty' => true
				, 'default' => is_null($expiry) ? '' : $expiry
				]);
			$i++;
		}
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
