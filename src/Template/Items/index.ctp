<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Item'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="items index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('id', __('Itin')) ?></th>
			<th><?= $this->Paginator->sort('name') ?><br>
				<?= $this->Paginator->sort('description') ?></th>
			<th><?= $this->Paginator->sort('character_id') ?></th>
			<th><?= $this->Paginator->sort('expiry') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($items as $item): ?>
		<tr>
			<td><?= h($item->id) ?></td>
			<td><i><?= h($item->name) ?></i><br>
				<?= h($item->description) ?></td>
			<td>
				<?= $item->has('character') ? $this->Html->link($item->character->name, ['controller' => 'Characters', 'action' => 'view', $item->character->id]) : '' ?>
			</td>
			<td><?= h($item->expiry) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $item->id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $item->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $item->id], ['confirm' => __('Are you sure you want to delete # {0}?', $item->id)]) ?>
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
