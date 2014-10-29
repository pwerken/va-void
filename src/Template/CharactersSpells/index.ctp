<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Characters Spell'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Spells'), ['controller' => 'Spells', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Spell'), ['controller' => 'Spells', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersSpells index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('character_id') ?></th>
			<th><?= $this->Paginator->sort('spell_id') ?></th>
			<th><?= $this->Paginator->sort('level') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($charactersSpells as $charactersSpell): ?>
		<tr>
			<td>
				<?= $charactersSpell->has('character') ? $this->Html->link($charactersSpell->character->name, ['controller' => 'Characters', 'action' => 'view', $charactersSpell->character->id]) : '' ?>
			</td>
			<td>
				<?= $charactersSpell->has('spell') ? $this->Html->link($charactersSpell->spell->name, ['controller' => 'Spells', 'action' => 'view', $charactersSpell->spell->id]) : '' ?>
			</td>
			<td><?= $this->Number->format($charactersSpell->level) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $charactersSpell->character_id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $charactersSpell->character_id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $charactersSpell->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersSpell->character_id)]) ?>
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
