<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Attribute'), ['action' => 'edit', $attribute->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Attribute'), ['action' => 'delete', $attribute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attribute->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Attributes'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attribute'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="attributes view large-10 medium-9 columns">
	<h2><?= h($attribute->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($attribute->name) ?></p>
			<h6 class="subheader"><?= __('Code') ?></h6>
			<p><?= h($attribute->code) ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= h($attribute->id) ?></p>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('LorType') ?></h6>
			<?= $this->Text->autoParagraph(h($attribute->lorType)); ?>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Items') ?></h4>
	<?php if (!empty($attribute->items)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Itin') ?></th>
			<th>
				<i><?= __('Name') ?></i><br>
				<?= __('Description') ?>
			</th>
			<th><?= __('Player Text') ?></th>
			<th><?= __('Character') ?></th>
			<th><?= __('Expiry') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($attribute->items as $items): ?>
		<tr>
			<td><?= h($items->id) ?></td>
			<td><i><?= h($items->name) ?></i><br>
				<?= h($items->description) ?></td>
			<td><?= h($items->player_text) ?></td>
			<td><?= $items->has('character') ? $this->Html->link($items->character->player_id.'-'.$items->character->chin.' '.$items->character->name, ['controller' => 'Characters', 'action' => 'view', $items->character->id]) : '' ?></td>
			<td><?= is_object($items->expiry) ? $items->expiry->format('d-m-Y') : __('Permanent') ?></td>
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
