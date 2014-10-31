<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Belief'), ['action' => 'edit', $belief->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Belief'), ['action' => 'delete', $belief->id], ['confirm' => __('Are you sure you want to delete # {0}?', $belief->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Believes'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Belief'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="believes view large-10 medium-9 columns">
	<h2><?= h($belief->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($belief->name) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= h($belief->id) ?></p>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Characters') ?></h4>
	<?php if (!empty($belief->characters)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Plin') ?></th>
			<th><?= __('Chin') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('Xp') ?></th>
			<th><?= __('Faction') ?></th>
			<th><?= __('Belief') ?></th>
			<th><?= __('Group') ?></th>
			<th><?= __('World') ?></th>
			<th><?= __('Status') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($belief->characters as $characters): ?>
		<tr>
			<td><?= h($characters->player_id) ?></td>
			<td><?= h($characters->chin) ?></td>
			<td><?= h($characters->name) ?></td>
			<td><?= h($characters->xp) ?></td>
			<td><?= $characters->has('faction') ? $this->Html->link($characters->faction->name, ['controller' => 'Factions', 'action' => 'view', $characters->faction->id]) : '' ?></td>
			<td><?= $characters->has('belief') ? $this->Html->link($characters->belief->name, ['controller' => 'Believes', 'action' => 'view', $characters->belief->id]) : '' ?></td>
			<td><?= $characters->has('group') ? $this->Html->link($characters->group->name, ['controller' => 'Groups', 'action' => 'view', $characters->group->id]) : '' ?></td>
			<td><?= $characters->has('world') ? $this->Html->link($characters->world->name, ['controller' => 'Worlds', 'action' => 'view', $characters->world->id]) : '' ?></td>
			<td><?= h($characters->status) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Characters', 'action' => 'view', $characters->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Characters', 'action' => 'edit', $characters->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Characters', 'action' => 'delete', $characters->id], ['confirm' => __('Are you sure you want to delete # {0}?', $characters->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
