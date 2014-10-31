<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Skill'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Manatypes'), ['controller' => 'Manatypes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Manatype'), ['controller' => 'Manatypes', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="skills index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('name') ?></th>
			<th><?= $this->Paginator->sort('cost') ?></th>
			<th><?= __('Mana') ?></th>
			<th><?= $this->Paginator->sort('sort_order') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($skills as $skill): ?>
		<tr>
			<td><?= h($skill->name) ?></td>
			<td><?= $this->Number->format($skill->cost) ?></td>
			<td>
				<?= $skill->has('manatype') ? $this->Number->format($skill->mana_amount) . ' ' . h($skill->manatype->name) : '' ?>
			</td>
			<td><?= $this->Number->format($skill->sort_order) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $skill->id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $skill->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $skill->id], ['confirm' => __('Are you sure you want to delete # {0}?', $skill->id)]) ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<div class="paginator">
		<ul class="pagination">
		<?php
			echo $this->Paginator->prev('< ' . __('previous'));
			echo $this->Paginator->numbers();
			echo $this->Paginator->next(__('next') . ' >');
		?>
		</ul>
		<p><?= $this->Paginator->counter() ?></p>
	</div>
</div>
