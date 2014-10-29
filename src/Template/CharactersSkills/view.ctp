<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Characters Skill'), ['action' => 'edit', $charactersSkill->character_id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Characters Skill'), ['action' => 'delete', $charactersSkill->character_id], ['confirm' => __('Are you sure you want to delete # {0}?', $charactersSkill->character_id)]) ?> </li>
		<li><?= $this->Html->link(__('List Characters Skills'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Characters Skill'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('List Skills'), ['controller' => 'Skills', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Skill'), ['controller' => 'Skills', 'action' => 'add']) ?> </li>
	</ul>
</div>
<div class="charactersSkills view large-10 medium-9 columns">
	<h2><?= h($charactersSkill->character_id) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Character') ?></h6>
			<p><?= $charactersSkill->has('character') ? $this->Html->link($charactersSkill->character->name, ['controller' => 'Characters', 'action' => 'view', $charactersSkill->character->id]) : '' ?></p>
			<h6 class="subheader"><?= __('Skill') ?></h6>
			<p><?= $charactersSkill->has('skill') ? $this->Html->link($charactersSkill->skill->name, ['controller' => 'Skills', 'action' => 'view', $charactersSkill->skill->id]) : '' ?></p>
		</div>
	</div>
</div>
