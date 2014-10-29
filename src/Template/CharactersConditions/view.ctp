<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Characters Condition'), ['action' => 'edit', $charactersCondition->character_id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Characters Condition'), ['action' => 'delete', $charactersCondition->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersCondition->character_id)]) ?> </li>
		<li><?= $this->Html->link(__('List Characters Conditions'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Characters Condition'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Conditions'), ['controller' => 'Conditions', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Condition'), ['controller' => 'Conditions', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersConditions view large-10 medium-9 columns">
	<h2><?= h($charactersCondition->character_id) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Character') ?></h6>
			<p><?= $charactersCondition->has('character') ? $this->Html->link($charactersCondition->character->name, ['controller' => 'Characters', 'action' => 'view', $charactersCondition->character->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Condition') ?></h6>
			<p><?= $charactersCondition->has('condition') ? $this->Html->link($charactersCondition->condition->name, ['controller' => 'Conditions', 'action' => 'view', $charactersCondition->condition->id]) : '' ?></p>
		</div>
		<div class="large-2 columns dates end">
			<h6 class="subheader"><?= __('Expiry') ?></h6>
			<p><?= h($charactersCondition->expiry) ?></p>
		</div>
	</div>
</div>
