<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Attributes Item'), ['action' => 'edit', $attributesItem->attribute_id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Attributes Item'), ['action' => 'delete', $attributesItem->attribute_id], ['confirm' => __('Are you sure you want to delete # {0}?', $attributesItem->attribute_id)]) ?> </li>
		<li><?= $this->Html->link(__('List Attributes Items'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attributes Item'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Items'), ['controller' => 'Items', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Item'), ['controller' => 'Items', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="attributesItems view large-10 medium-9 columns">
	<h2><?= h($attributesItem->attribute_id) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Attribute') ?></h6>
			<p><?= $attributesItem->has('attribute') ? $this->Html->link($attributesItem->attribute->name, ['controller' => 'Attributes', 'action' => 'view', $attributesItem->attribute->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Item') ?></h6>
			<p><?= $attributesItem->has('item') ? $this->Html->link($attributesItem->item->name, ['controller' => 'Items', 'action' => 'view', $attributesItem->item->id]) : '' ?></p>
		</div>
	</div>
</div>
