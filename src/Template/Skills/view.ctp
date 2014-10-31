<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Skill'), ['action' => 'edit', $skill->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Skill'), ['action' => 'delete', $skill->id], ['confirm' => __('Are you sure you want to delete # {0}?', $skill->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Skills'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Skill'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Manatypes'), ['controller' => 'Manatypes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Manatype'), ['controller' => 'Manatypes', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="skills view large-10 medium-9 columns">
	<h2><?= h($skill->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($skill->name) ?></p>
			<h6 class="subheader"><?= __('Manatype') ?></h6>
			<p><?= $skill->has('manatype') ? $this->Html->link($skill->manatype->name, ['controller' => 'Manatypes', 'action' => 'view', $skill->manatype->id]) : '' ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= h($skill->id) ?></p>
			<h6 class="subheader"><?= __('Cost') ?></h6>
			<p><?= $this->Number->format($skill->cost) ?></p>
			<h6 class="subheader"><?= __('Mana Amount') ?></h6>
			<p><?= $this->Number->format($skill->mana_amount) ?></p>
			<h6 class="subheader"><?= __('Sort Order') ?></h6>
			<p><?= $this->Number->format($skill->sort_order) ?></p>
		</div>
	</div>
</div>
