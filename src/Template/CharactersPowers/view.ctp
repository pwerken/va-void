<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Characters Power'), ['action' => 'edit', $charactersPower->character_id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Characters Power'), ['action' => 'delete', $charactersPower->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersPower->character_id)]) ?> </li>
		<li><?= $this->Html->link(__('List Characters Powers'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Characters Power'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Powers'), ['controller' => 'Powers', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Power'), ['controller' => 'Powers', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersPowers view large-10 medium-9 columns">
	<h2><?= h($charactersPower->character_id) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Character') ?></h6>
			<p><?= $charactersPower->has('character') ? $this->Html->link($charactersPower->character->name, ['controller' => 'Characters', 'action' => 'view', $charactersPower->character->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Power') ?></h6>
			<p><?= $charactersPower->has('power') ? $this->Html->link($charactersPower->power->name, ['controller' => 'Powers', 'action' => 'view', $charactersPower->power->id]) : '' ?></p>
		</div>
		<div class="large-2 columns dates end">
			<h6 class="subheader"><?= __('Expiry') ?></h6>
			<p><?= $charactersPower->has('expiry') ? '' : $charactersPower->expiry->format('d-m-Y') ?></p>
		</div>
	</div>
</div>
