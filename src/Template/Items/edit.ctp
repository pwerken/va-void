<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $item->id], ['confirm' => __('Are you sure you want to delete # {0}?', $item->id)]) ?></li>
		<li><?= $this->Html->link(__('List Items'), ['action' => 'index']) ?></li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Attributes'), ['controller' => 'Attributes', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Attribute'), ['controller' => 'Attributes', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="items form large-10 medium-9 columns">
<?= $this->Form->create($item) ?>
	<fieldset>
		<legend><?= __('Edit Item') ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('player_text', [ 'rows' => 5 ]);
		echo $this->Form->input('cs_text', [ 'rows' => 5 ]);
		echo $this->Form->input('character_id',
				[ 'options' => $characters
				, 'empty' => true
				]);

		$val = $item->expiry;
		echo $this->Form->input('expiry',
				[ 'minYear' => date('Y')
				, 'maxYear' => date('Y') + 5
				, 'empty' => true
				, 'val' => is_null($val) ? '' : $val
				]);

        $options = \Cake\ORM\TableRegistry::get('attributes')
					->find('list',
						[ 'idField' => 'id'
						, 'valueField' => 'name'
						, 'groupField' => 'lorType'
						])
					->where(['lorType NOT LIKE' => 'random'])
					->order(['name'])
					->toArray();

		$attr_ids = [];
		foreach($item->attributes as $attr)
			$attr_ids[$attr['lorType']][] = $attr['id'];

		$fields =	[ __('Special')		=> 'special'
					, __('Magical')		=> 'magic'
					, __('Spiritual')	=> 'spirit'
					, __('Value')		=>  'value'
					, __('Material #1')	=> 'material'
					, __('Material #2')	=> 'material'
					, __('Forgery')		=> 'forgery'
					, __('Damage #1')	=> 'damage'
					, __('Damage #2')	=> 'damage'
					];
		$i = 0;
		foreach($fields as $label => $attr) {
			echo $this->Form->input('attributes._ids.'.$i++,
					[ 'type' => 'select', 'empty' => true
					, 'label' => $label
					, 'options' => $options[$attr]
					, 'val' => isset($attr_ids[$attr][0])
							? array_shift($attr_ids[$attr]) : ''
					]);
		}
	?>
	</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
</div>
