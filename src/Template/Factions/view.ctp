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
			<p><?= $this->Number->format($faction->id) ?></p>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Characters') ?></h4>
	<?php if (!empty($faction->characters)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Id') ?></th>
			<th><?= __('Player Id') ?></th>
			<th><?= __('Chin') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('Xp') ?></th>
			<th><?= __('Faction Id') ?></th>
			<th><?= __('Belief Id') ?></th>
			<th><?= __('Group Id') ?></th>
			<th><?= __('World Id') ?></th>
			<th><?= __('Status') ?></th>
			<th><?= __('Comments') ?></th>
			<th><?= __('Created') ?></th>
			<th><?= __('Modified') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($faction->characters as $characters): ?>
		<tr>
			<td><?= h($characters->id) ?></td>
			<td><?= h($characters->player_id) ?></td>
			<td><?= h($characters->chin) ?></td>
			<td><?= h($characters->name) ?></td>
			<td><?= h($characters->xp) ?></td>
			<td><?= h($characters->faction_id) ?></td>
			<td><?= h($characters->belief_id) ?></td>
			<td><?= h($characters->group_id) ?></td>
			<td><?= h($characters->world_id) ?></td>
			<td><?= h($characters->status) ?></td>
			<td><?= h($characters->comments) ?></td>
			<td><?= h($characters->created) ?></td>
			<td><?= h($characters->modified) ?></td>
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
