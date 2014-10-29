<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Characters Skill'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersSkills index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('character_id') ?></th>
			<th><?= $this->Paginator->sort('skill_id') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($charactersSkills as $charactersSkill): ?>
		<tr>
			<td>
				<?= $charactersSkill->has('character') ? $this->Html->link($charactersSkill->character->name, ['controller' => 'Characters', 'action' => 'view', $charactersSkill->character->id]) : '' ?>
			</td>
			<td>
				<?= $charactersSkill->has('skill') ? $this->Html->link($charactersSkill->skill->name, ['controller' => 'Skills', 'action' => 'view', $charactersSkill->skill->id]) : '' ?>
			</td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $charactersSkill->character_id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $charactersSkill->character_id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $charactersSkill->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersSkill->character_id)]) ?>
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
