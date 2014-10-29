<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Player'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="players index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('id') ?></th>
			<th><?= $this->Paginator->sort('username') ?></th>
			<th><?= $this->Paginator->sort('password') ?></th>
			<th><?= $this->Paginator->sort('first_name') ?></th>
			<th><?= $this->Paginator->sort('insertion') ?></th>
			<th><?= $this->Paginator->sort('last_name') ?></th>
			<th><?= $this->Paginator->sort('date_of_birth') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($players as $player): ?>
		<tr>
			<td><?= $this->Number->format($player->id) ?></td>
			<td><?= h($player->username) ?></td>
			<td><?= h($player->password) ?></td>
			<td><?= h($player->first_name) ?></td>
			<td><?= h($player->insertion) ?></td>
			<td><?= h($player->last_name) ?></td>
			<td><?= h($player->date_of_birth) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $player->id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $player->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $player->id], ['confirm' => __('Are you sure you want to delete # {0}?', $player->id)]) ?>
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
