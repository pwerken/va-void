<?php

if(isset($user)) {
	echo "<p>Logged in as: ".$user['full_name']."<br/>";
	echo "With auth level: ".$user['role']."</p>";
	echo $this->Html->link(__('Click here to logout.'), '/admin/logout');
} else {
	echo $this->Form->create();
	echo "<fieldset>\n";
	echo $this->Form->input('id', ['label' => 'Plin', 'type' => 'text']);
	echo $this->Form->input('password', ['type'=>'password']);
	echo $this->Form->button(__('Login'));
	echo "</fieldset>\n";
	echo $this->Form->end();
}
