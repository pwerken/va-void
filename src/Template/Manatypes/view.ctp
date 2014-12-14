<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Manatype'), ['action' => 'edit', $manatype->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Manatype'), ['action' => 'delete', $manatype->id], ['confirm' => __('Are you sure you want to delete # {0}?', $manatype->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Manatypes'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Manatype'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="manatypes view large-10 medium-9 columns">
	<h2><?= h($manatype->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($manatype->name) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= h($manatype->id) ?></p>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Skills') ?></h4>
	<?php if (!empty($manatype->skills)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Id') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('Cost') ?></th>
			<th><?= __('Manatype Id') ?></th>
			<th><?= __('Mana Amount') ?></th>
			<th><?= __('Sort Order') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($manatype->skills as $skill): ?>
		<tr>
			<td><?= h($skill->id) ?></td>
			<td><?= h($skill->name) ?></td>
			<td><?= h($skill->cost) ?></td>
			<td><?= h($skill->manatype_id) ?></td>
			<td><?= h($skill->mana_amount) ?></td>
			<td><?= h($skill->sort_order) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Skills', 'action' => 'view', $skill->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Skills', 'action' => 'edit', $skill->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Skills', 'action' => 'delete', $skill->id], ['confirm' => __('Are you sure you want to delete # {0}?', $skill->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
