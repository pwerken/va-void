<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Debug links') ?></li>
<?php
foreach($links as $url => $descr)
	echo '<li>'.$this->Html->link($descr, $url)."</il>\n";
?>
    </ul>
</nav>
<div class="players index large-9 medium-8 columns content">
	<h3>Set authorisation</h3>
<?php

$options = [];
foreach($roles as $role) {
	$options[] = [ 'value' => $role, 'text' => $role];
}

echo $this->Form->create();
echo $this->Form->input('plin', ['label' => 'Plin', 'type' => 'text']);
echo $this->Form->input('role', [ 'label' => 'Role', 'type' => 'radio'
		, 'options' => $options, 'value' => 'Player']);
echo $this->Form->button(__('Set'));

?>
</div>
