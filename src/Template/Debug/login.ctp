<?php

use Cake\ORM\TableRegistry;

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
    <h3>Authentication</h3>
<?php

if($user) {
	echo "<p>Logged in as: ".$user['full_name']."<br/>";
	echo "With auth level: ".$user['role']."</p>";
	echo $this->Html->link(__('Click here to logout.'), '/auth/logout');
} else {
	echo $this->Form->create('', ['url' => '/auth/login']);
	echo "<fieldset>\n";
	echo $this->Form->input('id', ['label' => 'Plin', 'type' => 'text']);
	echo $this->Form->input('password', ['type'=>'password']);
	echo $this->Form->button(__('Login'));
	echo "</fieldset>\n";
	echo $this->Form->end();
}

?>
</div>
