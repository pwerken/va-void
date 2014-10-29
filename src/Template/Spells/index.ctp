<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Spell'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="spells index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('id') ?></th>
			<th><?= $this->Paginator->sort('name') ?></th>
			<th><?= $this->Paginator->sort('short') ?></th>
			<th><?= $this->Paginator->sort('spiritual') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($spells as $spell): ?>
		<tr>
			<td><?= $this->Number->format($spell->id) ?></td>
			<td><?= h($spell->name) ?></td>
			<td><?= h($spell->short) ?></td>
			<td><?= h($spell->spiritual) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $spell->id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $spell->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $spell->id], ['confirm' => __('Are you sure you want to delete # {0}?', $spell->id)]) ?>
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
