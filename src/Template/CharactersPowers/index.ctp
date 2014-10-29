<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Characters Power'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Powers'), ['controller' => 'Powers', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Power'), ['controller' => 'Powers', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersPowers index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('character_id') ?></th>
			<th><?= $this->Paginator->sort('power_id') ?></th>
			<th><?= $this->Paginator->sort('expiry') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($charactersPowers as $charactersPower): ?>
		<tr>
			<td>
				<?= $charactersPower->has('character') ? $this->Html->link($charactersPower->character->name, ['controller' => 'Characters', 'action' => 'view', $charactersPower->character->id]) : '' ?>
			</td>
			<td>
				<?= $charactersPower->has('power') ? $this->Html->link($charactersPower->power->name, ['controller' => 'Powers', 'action' => 'view', $charactersPower->power->id]) : '' ?>
			</td>
			<td><?= h($charactersPower->expiry) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $charactersPower->character_id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $charactersPower->character_id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $charactersPower->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersPower->character_id)]) ?>
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
