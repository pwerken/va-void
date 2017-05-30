<?php
use Cake\Auth\DefaultPasswordHasher;

?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Debug links') ?></li>
        <li><?= $this->Html->link(__('View Configured Routes'), '/debug/routes') ?></li>
        <li><?= $this->Html->link(__('View Authorisations'), '/debug/auth') ?></li>
        <li><?= $this->Html->link(__('Create DB Password Hash'), '/debug/hash') ?></li>
        <li><?= $this->Html->link(__('Account Login / Logout'), '/debug/login') ?></li>
        <li><?= $this->Html->link(__('Set Player Password'), '/debug/password') ?></li>
        <li><?= $this->Html->link(__('Set Authorisation'), '/debug/role') ?></li>
    </ul>
</nav>
<div class="players index large-9 medium-8 columns content">
    <h3>Create database password hash</h3>
<?php

$hash = '&nbsp;';
if(!empty($this->request->data('password'))) {
	$hasher = new DefaultPasswordHasher();
	$hash = $hasher->hash($this->request->data('password'));
}

echo $this->Form->create();
echo $this->Form->input('password'
		, ['label' => 'Password', 'type' => 'password']);
echo "<p>$hash</p>";
echo $this->Form->button(__('Generate'));

?>
</div>