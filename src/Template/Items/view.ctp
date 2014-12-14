<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Item'), ['action' => 'edit', $item->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Item'), ['action' => 'delete', $item->id], ['confirm' => __('Are you sure you want to delete # {0}?', $item->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Items'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Item'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="items view large-10 medium-9 columns">
	<h2><?= h($item->name) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Name') ?></h6>
			<p><?= h($item->name) ?></p>
			<h6 class="subheader"><?= __('Description') ?></h6>
			<p><?= h($item->description) ?></p>
			<h6 class="subheader"><?= __('Character') ?></h6>
			<p><?= $item->has('character') ?  $this->Html->link($item->character->player_id.'-'.$item->character->chin.' '.$item->character->name, ['controller' => 'Characters', 'action' => 'view', $item->character->player_id, $item->character->chin]) : '' ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Itin') ?></h6>
			<p><?= h($item->id) ?></p>
		</div>
		<div class="large-2 columns dates end">
			<h6 class="subheader"><?= __('Expiry') ?></h6>
			<p><?= $this->Date->dmy($item->expiry) ?></p>
			<h6 class="subheader"><?= __('Created') ?></h6>
			<p><?= $this->Date->full($item->created) ?></p>
			<h6 class="subheader"><?= __('Modified') ?></h6>
			<p><?= $this->Date->full($item->modified) ?></p>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Player Text') ?></h6>
			<?= $this->Text->autoParagraph(h($item->player_text)); ?>
		</div>
	</div>
	<div class="row texts">
		<div class="columns large-9">
			<h6 class="subheader"><?= __('Cs Text') ?></h6>
			<?= $this->Text->autoParagraph(h($item->cs_text)); ?>
		</div>
	</div>
</div>
<div class="related row">
	<div class="column large-12">
	<h4 class="subheader"><?= __('Related Attributes') ?></h4>
	<?php if (!empty($item->attributes)): ?>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?= __('Name') ?></th>
			<th><?= __('Category') ?></th>
			<th><?= __('Code') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
		<?php foreach ($item->attributes as $attribute): ?>
		<tr>
			<td><?= h($attribute->name) ?></td>
			<td><?= h($attribute->category) ?></td>
			<td><?= h($attribute->code) ?></td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['controller' => 'Attributes', 'action' => 'view', $attribute->id]) ?>
				<?= $this->Html->link(__('Edit'), ['controller' => 'Attributes', 'action' => 'edit', $attribute->id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['controller' => 'Attributes', 'action' => 'delete', $attribute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $attribute->id)]) ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	</div>
</div>
