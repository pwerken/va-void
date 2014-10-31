<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Spell'), ['action' => 'edit', $spell->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Spell'), ['action' => 'delete', $spell->id], ['confirm' => __('Are you sure you want to delete # {0}?', $spell->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Spells'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Spell'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="spells view large-10 medium-9 columns">
	<h2><?= h($spell->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($spell->name) ?></p>
			<h6 class="subheader"><?= __('Short') ?></h6>
			<p><?= h($spell->short) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= h($spell->id) ?></p>
		</div>
		<div class="large-2 columns booleans end">
			<h6 class="subheader"><?= __('Spiritual') ?></h6>
			<p><?= $spell->spiritual ? __('Yes') : __('No'); ?></p>
		</div>
	</div>
</div>
