<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Character'), ['action' => 'edit', $character->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Character'), ['action' => 'delete', $character->id], ['confirm' => __('Are you sure you want to delete # {0}?', $character->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Players'), ['controller' => 'Players', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Player'), ['controller' => 'Players', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Factions'), ['controller' => 'Factions', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Faction'), ['controller' => 'Factions', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Believes'), ['controller' => 'Believes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Belief'), ['controller' => 'Believes', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Worlds'), ['controller' => 'Worlds', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New World'), ['controller' => 'Worlds', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Conditions'), ['controller' => 'Conditions', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Condition'), ['controller' => 'Conditions', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Powers'), ['controller' => 'Powers', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Power'), ['controller' => 'Powers', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Spells'), ['controller' => 'Spells', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Spell'), ['controller' => 'Spells', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="characters view large-10 medium-9 columns">
	<h2><?= h($character->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Player') ?></h6>
			<p><?= $character->has('player') ? $this->Html->link($character->player->full_name, ['controller' => 'Players', 'action' => 'view', $character->player->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($character->name) ?></p>
			<h6 class="subheader"><?= __('Faction') ?></h6>
			<p><?= $character->has('faction') ? $this->Html->link($character->faction->name, ['controller' => 'Factions', 'action' => 'view', $character->faction->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Belief') ?></h6>
			<p><?= $character->has('belief') ? $this->Html->link($character->belief->name, ['controller' => 'Believes', 'action' => 'view', $character->belief->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Group') ?></h6>
			<p><?= $character->has('group') ? $this->Html->link($character->group->name, ['controller' => 'Groups', 'action' => 'view', $character->group->id]) : '' ?></p>
			<h6 class="subheader"><?= __('World') ?></h6>
			<p><?= $character->has('world') ? $this->Html->link($character->world->name, ['controller' => 'Worlds', 'action' => 'view', $character->world->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Status') ?></h6>
			<p><?= h($character->status) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Plin') ?></h6>
			<p><?= $character->has('player') ?  h($character->player->id) : '' ?></p>
			<h6 class="subheader"><?= __('Chin') ?></h6>
			<p><?= h($character->chin) ?></p>
			<h6 class="subheader"><?= __('Xp') ?></h6>
			<p><?= $this->Number->format($character->xp) ?></p>
		</div>
		<div class="large-2 columns dates end">
			<h6 class="subheader"><?= __('Created') ?></h6>
			<p><?= $character->has('created') ? $character->created->format('d-m-Y H:i:s') : '' ?></p>
			<h6 class="subheader"><?= __('Modified') ?></h6>
			<p><?= $character->has('modified') ? $character->modified->format('d-m-Y H:i:s') : '' ?></p>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Comments') ?></h6>
			<?= $this->Text->autoParagraph(h($character->comments)); ?>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Items') ?></h4>
	<?php if (!empty($character->items)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Itin') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('Player Text') ?></th>
			<th><?= __('Expiry') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($character->items as $items): ?>
		<tr>
			<td><?= h($items->id) ?></td>
			<td><i><?= h($items->name) ?></i><br>
				<?= h($items->description) ?></td>
			<td><?= h($items->player_text) ?></td>
			<td><?= $items->has('expiry') ? $items->expiry->format('d-m-Y') : __('Permanent') ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Items', 'action' => 'view', $items->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Items', 'action' => 'edit', $items->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Items', 'action' => 'delete', $items->id], ['confirm' => __('Are you sure you want to delete # {0}?', $items->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Powers') ?></h4>
	<?php if (!empty($character->powers)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Poin') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('Player Text') ?></th>
			<th><?= __('Expiry') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($character->powers as $powers): ?>
		<tr>
			<td><?= h($powers->id) ?></td>
			<td><?= h($powers->name) ?></td>
			<td><?= h($powers->player_text) ?></td>
			<td><?= is_object($powers->_joinData['expiry']) ?  $powers->_joinData['expiry']->format('d-m-Y') : __('Permanent') ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Powers', 'action' => 'view', $powers->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Powers', 'action' => 'edit', $powers->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Powers', 'action' => 'delete', $powers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $powers->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Conditions') ?></h4>
	<?php if (!empty($character->conditions)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Coin') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('Player Text') ?></th>
			<th><?= __('Expiry') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($character->conditions as $conditions): ?>
		<tr>
			<td><?= h($conditions->id) ?></td>
			<td><?= h($conditions->name) ?></td>
			<td><?= h($conditions->player_text) ?></td>
			<td><?= is_object($conditions->_joinData['expiry']) ?  $conditions->_joinData['expiry']->format('d-m-Y') : __('Permanent') ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Conditions', 'action' => 'view', $conditions->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Conditions', 'action' => 'edit', $conditions->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Conditions', 'action' => 'delete', $conditions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $conditions->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Skills') ?></h4>
	<?php if (!empty($character->skills)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Name') ?></th>
			<th><?= __('Cost') ?></th>
			<th><?= __('Mana') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php $sum = 0; ?>
		<?php foreach ($character->skills as $skills): ?>
		<?php	$sum += $skills->cost; ?>
		<tr>
			<td><?= h($skills->name) ?></td>
			<td><?= h($skills->cost) ?></td>
			<td><?= $skills->has('manatype') ? h($skills->mana_amount) . ' ' . h($skills->manatype->name) : '' ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Skills', 'action' => 'view', $skills->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Skills', 'action' => 'edit', $skills->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Skills', 'action' => 'delete', $skills->id], ['confirm' => __('Are you sure you want to delete # {0}?', $skills->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php if($sum > 0): ?>
		<tr>
			<td><b><?= __('Total') ?></b></td>
			<td><?= h($sum) ?> / <?=$character->xp ?></td>
			<td></td>
			<td></td>
		</tr>
		<?php endif; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Spells') ?></h4>
	<?php if (!empty($character->spells)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Name') ?></th>
			<th><?= __('Level') ?></th>
			<th><?= __('Spiritual') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($character->spells as $spells): ?>
		<tr>
			<td><?= h($spells->name) ?></td>
			<td><?= $this->Number->format($spells->_joinData['level']) ?></td>
			<td><?= h($spells->spiritual) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Spells', 'action' => 'view', $spells->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Spells', 'action' => 'edit', $spells->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Spells', 'action' => 'delete', $spells->id], ['confirm' => __('Are you sure you want to delete # {0}?', $spells->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
