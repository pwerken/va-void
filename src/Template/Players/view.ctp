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
	<h2><?= h($player->id) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Username') ?></h6>
			<p><?= h($player->username) ?></p>
			<h6 class="subheader"><?= __('Password') ?></h6>
			<p><?= h($player->password) ?></p>
			<h6 class="subheader"><?= __('First Name') ?></h6>
			<p><?= h($player->first_name) ?></p>
			<h6 class="subheader"><?= __('Insertion') ?></h6>
			<p><?= h($player->insertion) ?></p>
			<h6 class="subheader"><?= __('Last Name') ?></h6>
			<p><?= h($player->last_name) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= $this->Number->format($player->id) ?></p>
		</div>
		<div class="large-2 columns dates end">
			<h6 class="subheader"><?= __('Date Of Birth') ?></h6>
			<p><?= h($player->date_of_birth) ?></p>
			<h6 class="subheader"><?= __('Created') ?></h6>
			<p><?= h($player->created) ?></p>
			<h6 class="subheader"><?= __('Modified') ?></h6>
			<p><?= h($player->modified) ?></p>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Account Type') ?></h6>
			<?= $this->Text->autoParagraph(h($player->account_type)); ?>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Gender') ?></h6>
			<?= $this->Text->autoParagraph(h($player->gender)); ?>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Characters') ?></h4>
	<?php if (!empty($player->characters)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Id') ?></th>
			<th><?= __('Player Id') ?></th>
			<th><?= __('Chin') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('Xp') ?></th>
			<th><?= __('Faction Id') ?></th>
			<th><?= __('Belief Id') ?></th>
			<th><?= __('Group Id') ?></th>
			<th><?= __('World Id') ?></th>
			<th><?= __('Status') ?></th>
			<th><?= __('Comments') ?></th>
			<th><?= __('Created') ?></th>
			<th><?= __('Modified') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($player->characters as $characters): ?>
		<tr>
			<td><?= h($characters->id) ?></td>
			<td><?= h($characters->player_id) ?></td>
			<td><?= h($characters->chin) ?></td>
			<td><?= h($characters->name) ?></td>
			<td><?= h($characters->xp) ?></td>
			<td><?= h($characters->faction_id) ?></td>
			<td><?= h($characters->belief_id) ?></td>
			<td><?= h($characters->group_id) ?></td>
			<td><?= h($characters->world_id) ?></td>
			<td><?= h($characters->status) ?></td>
			<td><?= h($characters->comments) ?></td>
			<td><?= h($characters->created) ?></td>
			<td><?= h($characters->modified) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Characters', 'action' => 'view', $characters->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Characters', 'action' => 'edit', $characters->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Characters', 'action' => 'delete', $characters->id], ['confirm' => __('Are you sure you want to delete # {0}?', $characters->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
