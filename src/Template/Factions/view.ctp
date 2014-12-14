<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Faction'), ['action' => 'edit', $faction->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Faction'), ['action' => 'delete', $faction->id], ['confirm' => __('Are you sure you want to delete # {0}?', $faction->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Factions'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Faction'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="factions view large-10 medium-9 columns">
	<h2><?= h($faction->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($faction->name) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= h($faction->id) ?></p>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Characters') ?></h4>
	<?php if (!empty($faction->characters)): ?>
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
		<?php foreach ($faction->characters as $character): ?>
		<tr>
			<td><?= h($character->displayName) ?></td>
			<td><?= h($character->xp) ?></td>
			<td><?= $character->has('faction') ? $this->Html->link($character->faction->name, ['controller' => 'Factions', 'action' => 'view', $character->faction->id]) : '' ?></td>
			<td><?= $character->has('belief') ? $this->Html->link($character->belief->name, ['controller' => 'Believes', 'action' => 'view', $character->belief->id]) : '' ?></td>
			<td><?= $character->has('group') ? $this->Html->link($character->group->name, ['controller' => 'Groups', 'action' => 'view', $character->group->id]) : '' ?></td>
			<td><?= $character->has('world') ? $this->Html->link($character->world->name, ['controller' => 'Worlds', 'action' => 'view', $character->world->id]) : '' ?></td>
			<td><?= h($character->status) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Characters', 'action' => 'view', $character->player_id, $character->chin]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Characters', 'action' => 'edit', $character->player_id, $character->chin]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Characters', 'action' => 'delete', $character->player_id, $character->chin], ['confirm' => __('Are you sure you want to delete # {0}?', $character->player_id, $character->chin)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
