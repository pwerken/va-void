<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Power'), ['action' => 'edit', $power->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Power'), ['action' => 'delete', $power->id], ['confirm' => __('Are you sure you want to delete # {0}?', $power->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Powers'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Power'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="powers view large-10 medium-9 columns">
	<h2><?= h($power->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($power->name) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Poin') ?></h6>
			<p><?= h($power->id) ?></p>
		</div>
		<div class="large-2 columns dates end">
			<h6 class="subheader"><?= __('Created') ?></h6>
			<p><?= $power->has('created') ? $power->created->format('d-m-Y H:i:s') : '' ?></p>
			<h6 class="subheader"><?= __('Modified') ?></h6>
			<p><?= $power->has('modified') ? $power->modified->format('d-m-Y H:i:s') : '' ?></p>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Player Text') ?></h6>
			<?= $this->Text->autoParagraph(h($power->player_text)); ?>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Cs Text') ?></h6>
			<?= $this->Text->autoParagraph(h($power->cs_text)); ?>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Characters') ?></h4>
	<?php if (!empty($power->characters)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Name') ?></th>
			<th><?= __('Expiry') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($power->characters as $characters): ?>
		<tr>
			<td><?= h($characters->displayName) ?></td>
			<td><?= is_object($characters->_joinData['expiry']) ? $characters->_joinData['expiry']->format('d-m-Y') : __('Permanent') ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Characters', 'action' => 'view', $characters->player_id, $characters->chin]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Characters', 'action' => 'edit', $characters->player_id, $characters->chin]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Characters', 'action' => 'delete', $characters->player_id, $characters->chin], ['confirm' => __('Are you sure you want to delete # {0}?', $characters->player_id, $characters->chin)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
