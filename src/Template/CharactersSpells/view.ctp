<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Characters Spell'), ['action' => 'edit', $charactersSpell->character_id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Characters Spell'), ['action' => 'delete', $charactersSpell->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersSpell->character_id)]) ?> </li>
		<li><?= $this->Html->link(__('List Characters Spells'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Characters Spell'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Spells'), ['controller' => 'Spells', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Spell'), ['controller' => 'Spells', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersSpells view large-10 medium-9 columns">
	<h2><?= h($charactersSpell->character_id) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Character') ?></h6>
			<p><?= $charactersSpell->has('character') ? $this->Html->link($charactersSpell->character->name, ['controller' => 'Characters', 'action' => 'view', $charactersSpell->character->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Spell') ?></h6>
			<p><?= $charactersSpell->has('spell') ? $this->Html->link($charactersSpell->spell->name, ['controller' => 'Spells', 'action' => 'view', $charactersSpell->spell->id]) : '' ?></p>
		</div>
		<div class="large-2 large-offset-1 columns numbers end">
			<h6 class="subheader"><?= __('Level') ?></h6>
			<p><?= $this->Number->format($charactersSpell->level) ?></p>
		</div>
	</div>
</div>
