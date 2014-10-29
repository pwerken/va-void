<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Characters Condition'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Conditions'), ['controller' => 'Conditions', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Condition'), ['controller' => 'Conditions', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersConditions index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('character_id') ?></th>
			<th><?= $this->Paginator->sort('condition_id') ?></th>
			<th><?= $this->Paginator->sort('expiry') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($charactersConditions as $charactersCondition): ?>
		<tr>
			<td>
				<?= $charactersCondition->has('character') ? $this->Html->link($charactersCondition->character->name, ['controller' => 'Characters', 'action' => 'view', $charactersCondition->character->id]) : '' ?>
			</td>
			<td>
				<?= $charactersCondition->has('condition') ? $this->Html->link($charactersCondition->condition->name, ['controller' => 'Conditions', 'action' => 'view', $charactersCondition->condition->id]) : '' ?>
			</td>
			<td><?= h($charactersCondition->expiry) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $charactersCondition->character_id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $charactersCondition->character_id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $charactersCondition->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersCondition->character_id)]) ?>
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
