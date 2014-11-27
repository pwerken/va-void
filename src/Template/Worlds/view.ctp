<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit World'), ['action' => 'edit', $world->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete World'), ['action' => 'delete', $world->id], ['confirm' => __('Are you sure you want to delete # {0}?', $world->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Worlds'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New World'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="worlds view large-10 medium-9 columns">
	<h2><?= h($world->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($world->name) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= h($world->id) ?></p>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Characters') ?></h4>
	<?php if (!empty($world->characters)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Name') ?></th>
			<th><?= __('Xp') ?></th>
			<th><?= __('Faction') ?></th>
			<th><?= __('Belief') ?></th>
			<th><?= __('Group') ?></th>
			<th><?= __('World') ?></th>
			<th><?= __('Status') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($world->characters as $characters): ?>
		<tr>
			<td><?= h($characters->displayName) ?></td>
			<td><?= h($characters->xp) ?></td>
			<td><?= $characters->has('faction') ? $this->Html->link($characters->faction->name, ['controller' => 'Factions', 'action' => 'view', $characters->faction->id]) : '' ?></td>
			<td><?= $characters->has('belief') ? $this->Html->link($characters->belief->name, ['controller' => 'Believes', 'action' => 'view', $characters->belief->id]) : '' ?></td>
			<td><?= $characters->has('group') ? $this->Html->link($characters->group->name, ['controller' => 'Groups', 'action' => 'view', $characters->group->id]) : '' ?></td>
			<td><?= $characters->has('world') ? $this->Html->link($characters->world->name, ['controller' => 'Worlds', 'action' => 'view', $characters->world->id]) : '' ?></td>
			<td><?= h($characters->status) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Characters', 'action' => 'view', $characters->player_id, $characters->chin]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Characters', 'action' => 'edit', $characters->player_id, $characters->chin]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Characters', 'action' => 'delete', $characters->player_id, $characters->chin], ['confirm' => __('Are you sure you want to delete # {0}?', $characters->player_id, $characters->chin)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
