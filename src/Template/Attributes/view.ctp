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
			<p><?= $this->Number->format($attribute->id) ?></p>
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
			<th><?= __('Id') ?></th>
			<th><?= __('Name') ?></th>
			<th><?= __('Dscription') ?></th>
			<th><?= __('Player Text') ?></th>
			<th><?= __('Cs Text') ?></th>
			<th><?= __('Character Id') ?></th>
			<th><?= __('Expiry') ?></th>
			<th><?= __('Created') ?></th>
			<th><?= __('Modified') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($attribute->items as $items): ?>
		<tr>
			<td><?= h($items->id) ?></td>
			<td><?= h($items->name) ?></td>
			<td><?= h($items->dscription) ?></td>
			<td><?= h($items->player_text) ?></td>
			<td><?= h($items->cs_text) ?></td>
			<td><?= h($items->character_id) ?></td>
			<td><?= h($items->expiry) ?></td>
			<td><?= h($items->created) ?></td>
			<td><?= h($items->modified) ?></td>
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
