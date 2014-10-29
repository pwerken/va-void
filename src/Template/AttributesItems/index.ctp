<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('New Attributes Item'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="attributesItems index large-10 medium-9 columns">
	<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('attribute_id') ?></th>
			<th><?= $this->Paginator->sort('item_id') ?></th>
			<th class="actions"><?= __('Actions') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($attributesItems as $attributesItem): ?>
		<tr>
			<td>
				<?= $attributesItem->has('attribute') ? $this->Html->link($attributesItem->attribute->name, ['controller' => 'Attributes', 'action' => 'view', $attributesItem->attribute->id]) : '' ?>
			</td>
			<td>
				<?= $attributesItem->has('item') ? $this->Html->link($attributesItem->item->name, ['controller' => 'Items', 'action' => 'view', $attributesItem->item->id]) : '' ?>
			</td>
			<td class="actions">
				<?= $this->Html->link(__('View'), ['action' => 'view', $attributesItem->attribute_id]) ?>
				<?= $this->Html->link(__('Edit'), ['action' => 'edit', $attributesItem->attribute_id]) ?>
				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $attributesItem->attribute_id], ['confirm' => __('Are you sure you want to delete # {0}?', $attributesItem->attribute_id)]) ?>
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
