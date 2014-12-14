<?php

use App\Model\Entity\Player;

?>
<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Player'), ['action' => 'edit', $player->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Player'), ['action' => 'delete', $player->id], ['confirm' => __('Are you sure you want to delete # {0}?', $player->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Players'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Player'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="players view large-10 medium-9 columns">
	<h2><?= h($player->full_name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('First Name') ?></h6>
			<p><?= h($player->first_name) ?></p>
			<h6 class="subheader"><?= __('Insertion') ?></h6>
			<p><?= h($player->insertion) ?></p>
			<h6 class="subheader"><?= __('Last Name') ?></h6>
			<p><?= h($player->last_name) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Plin') ?></h6>
			<p><?= h($player->id) ?></p>
		</div>
		<div class="large-2 columns dates end">
			<h6 class="subheader"><?= __('Date Of Birth') ?></h6>
			<p><?= $this->Date->dmy($player->date_of_birth) ?></p>
			<h6 class="subheader"><?= __('Created') ?></h6>
			<p><?= $this->Date->full($player->created) ?></p>
			<h6 class="subheader"><?= __('Modified') ?></h6>
			<p><?= $this->Date->full($player->modified) ?></p>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Account Type') ?></h6>
			<p><?= Player::labelAccountType($player->account_type); ?></p>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Gender') ?></h6>
			<p><?= Player::labelGender($player->gender); ?></p>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Characters') ?></h4>
	<?php if (!empty($player->characters)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Name') ?></th>
			<th><?= __('Xp') ?></th>
			<th><?= __('Faction') ?></th>
			<th><?= __('Belief') ?></th>
			<th><?= __('Group') ?></th>
			<th><?= __('World') ?></th>
			<th><?= __('Status') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($player->characters as $character): ?>
		<tr>
			<td><?= h($character->displayName) ?></td>
			<td><?= h($character->xp) ?></td>
			<td><?= $character->has('faction') ? $this->Html->link($character->faction->name, ['controller' => 'Factions', 'action' => 'view', $character->faction->id]) : '' ?></td>
			<td><?= $character->has('belief') ? $this->Html->link($character->belief->name, ['controller' => 'Believes', 'action' => 'view', $character->belief->id]) : '' ?></td>
			<td><?= $character->has('group') ? $this->Html->link($character->group->name, ['controller' => 'Groups', 'action' => 'view', $character->group->id]) : '' ?></td>
			<td><?= $character->has('world') ? $this->Html->link($character->world->name, ['controller' => 'Worlds', 'action' => 'view', $character->world->id]) : '' ?></td>
			<td><?= h($character->status) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Characters', 'action' => 'view', $character->player_id, $character->chin]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Characters', 'action' => 'edit', $character->player_id, $character->chin]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Characters', 'action' => 'delete', $character->player_id, $character->chin], ['confirm' => __('Are you sure you want to delete # {0}?', $character->player_id, $character->chin)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
